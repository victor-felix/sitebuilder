<?php

require 'lib/html_purifier/HTMLPurifier.auto.php';

class Articles extends AppModel {
    const ARTICLES_LIMIT = 20;
    protected static $blacklist = array(
        'gravatar.com'
    );
    protected $defaultScope = array(
        'order' => 'pubdate DESC'
    );
    protected $resizes = array('100x100');
    
    public function topByDomain($domain) {
        $feed = Model::load('Sites')->firstByDomain($domain)->feed();
        return $this->topByFeedId($feed->id);
    }
    
    public function articleExists($feed_id, $guid) {
        return $this->exists(compact('feed_id', 'guid'));
    }
    
    public function topByFeedId($feed_id) {
        return $this->all(array(
            'conditions' => array(
                'feed_id' => $feed_id
            ),
            'limit' => self::ARTICLES_LIMIT,
        ));
    }
    
    public function addToFeed($feed, $item) {
        $this->id = null;
        
        $article = array(
            'feed_id' => $feed->id,
            'guid' => $item->get_id(),
            'link' => $item->get_link(),
            'title' => $item->get_title(),
            'description' => $this->cleanupHtml($item->get_content()),
            'author' => $item->get_author()->get_name(),
            'pubdate' => $item->get_date('Y-m-d H:i:s')
        );
        
        try {
            $this->begin();
            
            $this->save($article);
            $enclosure = $this->getEnclosure($item);
            if($enclosure) {
                Model::load('Images')->download($this, $enclosure);
            }

            $this->commit();
        }
        catch(Exception $e) {
            $this->rollback();
            return false;
        }
    }
    
    protected function getEnclosure($item) {
        $enclosures = $item->get_enclosures();
        
        foreach($enclosures as $enclosure) {
            if($enclosure->get_medium() != 'image') continue;
            
            $link = $enclosure->get_link();
            if(!$this->isBlackListed($link)) {
                return $link;
            }
        }
        
        return null;
    }
    
    protected function isBlackListed($link) {
        foreach(self::$blacklist as $i) {
            $pattern = preg_quote($i);
            if(preg_match('%' . $pattern . '%', $link)) {
                return true;
            }
        }
        
        return false;
    }
    
    protected function getPurifier() {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', FileSystem::path('tmp/cache/html_purifier'));
        $config->set('HTML.Allowed', 'b,i,br');
        return new HTMLPurifier($config);
    }
    
    protected function cleanupHtml($html) {
        $purifier = $this->getPurifier();
        return $purifier->purify($html);
    }
}