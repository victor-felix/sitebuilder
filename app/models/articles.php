<?php

require_once 'app/models/business_items.php';
require_once 'lib/htmlpurifier/HTMLPurifier.auto.php';
require_once 'lib/dom/SimpleHtmlDom.php';

class Articles extends BusinessItems {
    protected $fields = array(
        'feed_id' => array(),
        'guid' => array(),
        'link' => array(),
        'pubdate' => array(),
        'format' => array(
            'default' => 'bbcode'
        ),
        'title' => array(
            'title' => 'Título',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Descrição',
            'type' => 'richtext'
        ),
        'author' => array(
            'title' => 'Autor',
            'type' => 'string'
        )
    );
    protected $scope = array(
        'order' => 'pubdate DESC'
    );
    protected static $blacklist = array(
        'gravatar.com'
    );

    public function fields() {
        return array('title', 'description', 'author');
    }

    public function topBySlug($slug) {
        $feed = Model::load('Sites')->firstBySlug($slug)->feed();
        return $this->topByFeedId($feed->id);
    }

    public function allByFeedId($feed_id, $limit = null) {
        return $this->all(array(
            'table' => array('a' => $this->table()),
            'fields' => 'a.*',
            'joins' => 'JOIN business_items_values AS v ' .
                'ON a.id = v.item_id',
            'conditions' => array(
                'v.field' => 'feed_id',
                'v.value' => $feed_id
            ),
            'limit' => $limit
        ));
    }

    public function topByFeedId($feed_id) {
        return $this->allByFeedId($feed_id, Config::read('Articles.limit'));
    }

    public function articleExists($feed_id, $guid) {
        $guid = $this->filterGuid($guid);
        $model = Model::load('BusinessItemsValues');
        return $model->itemExists(compact('feed_id', 'guid'));
    }

    public function addToFeed($feed, $item) {
        $log = KLogger::instance(Filesystem::path('log'));

        $this->id = null;

        $author = $item->get_author();
        $article = array(
            'site_id' => $feed->site_id,
            'feed_id' => $feed->id,
            'guid' => $this->filterGuid($item->get_id()),
            'link' => $item->get_link(),
            'title' => $item->get_title(),
            'description' => $this->cleanupHtml($item->get_content()),
            'author' => $author ? $author->get_name() : '',
            'pubdate' => $item->get_date('Y-m-d H:i:s'),
            'format' => 'html'
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
        // Remove first unecessary paragraph for PRODERJ purpose
        $body = implode(array_slice($description->find('p'), 1));
        return $purifier->purify($body);
    }

    protected function filterGuid($guid) {
        if(preg_match("%^http://www.rj.gov.br/web/guest/exibeconteudo;.*articleId=(\d+)%", $guid, $result)) {
            $guid = "http://www.rj.gov.br/web/guest/exibeconteudo?articleId=" . $result[1];
        }

        return $guid;
    }
}
