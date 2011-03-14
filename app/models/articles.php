<?php

require 'lib/html_purifier/HTMLPurifier.auto.php';
require 'lib/SimpleHtmlDom.php';

class Articles extends AppModel {
    protected static $blacklist = array(
        'gravatar.com'
    );
    protected $beforeDelete = array('deleteImages');
    protected $defaultScope = array(
        'order' => 'pubdate DESC'
    );

    public function topBySlug($slug) {
        $feed = Model::load('Sites')->firstBySlug($slug)->feed();
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
            'limit' => Config::read('Articles.limit')
        ));
    }

    public function addToFeed($feed, $item) {
        $this->id = null;

        $author = $item->get_author();
        $article = array(
            'feed_id' => $feed->id,
            'guid' => $item->get_id(),
            'link' => $item->get_link(),
            'title' => $item->get_title(),
            'description' => $this->cleanupHtml($item->get_content()),
            'author' => $author ? $author->get_name() : '',
            'pubdate' => $item->get_date('Y-m-d H:i:s')
        );

        try {
            $this->begin();

            $this->save($article);
            $images = $this->getImages($item);
            foreach($images as $image) {
                Model::load('Images')->download($this, $image, array(
                    'url' => $image
                ));
            }

            $this->commit();
        }
        catch(Exception $e) {
            $this->rollback();
            return false;
        }
    }

    protected function getImages($item) {
        $images = $this->getEnclosureImages($item);
        if(empty($images)) {
            $images = $this->getContentImages($item);
        }

        foreach($images as $k => $image) {
            if($this->isBlackListed($image)) {
                unset($images[$k]);
            }
        }

        return $images;
    }

    protected function getContentImages($item) {
        $content = str_get_html($item->get_content());
        $links = $content->find('a[rel=lightbox]');
        
        $images = array();

        foreach($links as $link) {
            $images []= $link->href;
        }
        
        return $images;
    }

    protected function getEnclosureImages($item) {
        $images = array();
        $enclosures = $item->get_enclosures();
        if(is_null($enclosures)) return $images;

        foreach($enclosures as $enclosure) {
            if($enclosure->get_medium() == 'image') {
                $images []= $enclosure->get_link();
            }
        }

        return $images;
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