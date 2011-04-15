<?php

require 'lib/htmlpurifier/HTMLPurifier.auto.php';
require 'lib/dom/SimpleHtmlDom.php';

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

    public function toJSON() {
        $images = Model::load('Images')->allByRecord('Articles', $this->id);

        foreach($images as $k => $image) {
            $images[$k] = $image->toJSON();
        }

        $this->images = $images;

        return $this->data;
    }

    public function addToFeed($feed, $item) {
        $log = KLogger::instance(Filesystem::path('log'));

        $this->id = null;

        $author = $item->get_author();
        $article = array(
            'feed_id' => $feed->id,
            'guid' => $this->filterGuid($item->get_id()),
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
                $image = $this->getImageUrl($image, $article['guid']);
                $result = Model::load('Images')->download($this, $image, array(
                    'url' => $image
                ));

                if($result) {
                    $log->logInfo('Downloaded image "%s" to "%s"', $image, $result);
                }
                else {
                    $log->logInfo('Image "%s" could not be found', $image, $result);
                }
            }

            $this->commit();

            $log->logInfo('Imported article "%s"', $article['guid']);
        }
        catch(Exception $e) {
            $this->rollback();

            $log->logInfo('Could not import article "%s". Exception: %s', $article['guid'], (string) $e);

            return false;
        }
    }

    protected function filterGuid($guid) {
        if(preg_match("%^http://www.rj.gov.br/web/guest/exibeconteudo;.*articleId=(\d+)%", $guid, $result)) {
            $guid = "http://www.rj.gov.br/web/guest/exibeconteudo?articleId=" . $result[1];
        }

        return $guid;
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

    protected function getImageUrl($url, $article) {
        if(Mapper::isRoot($url)) {
            $domain = parse_url($article, PHP_URL_HOST);
            $url = 'http://' . $domain . $url;
        }
        else if(preg_match('%^(http://download.rj.gov.br/imagens/\d+/\d+/\d+.jpg)%', $url, $output)) {
            return $output[0];
        }

        return $url;
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
        $config->set('HTML.Allowed', 'b,i,br,p');
        return new HTMLPurifier($config);
    }

    protected function cleanupHtml($html) {
        $purifier = $this->getPurifier();
        $description = str_get_html($html);
        $body = implode($description->find('p'));

        return $purifier->purify($body);
    }
}
