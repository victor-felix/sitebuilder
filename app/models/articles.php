<?php

require_once 'app/models/business_items.php';
require_once 'lib/htmlpurifier/HTMLPurifier.auto.php';
require_once 'lib/dom/SimpleHtmlDom.php';

class Articles extends BusinessItems {
    protected $typeName = 'Article';
    protected $fields = array(
        'guid' => array(),
        'link' => array(),
        'pubdate' => array(),
        'format' => array(
            'default' => 'bbcode'
        ),
        'title' => array(
            'title' => 'Title',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Description',
            'type' => 'richtext'
        ),
        'author' => array(
            'title' => 'Author',
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

    public function articleExists($feed_id, $guid) {
        $guid = $this->filterGuid($guid);
        return $this->itemExists(array(
            'item.parent_id' => $feed_id,
            'values.field' => 'guid',
            'values.value' => $guid
        ));
    }

    public function addToFeed($feed, $item) {
        $log = KLogger::instance(Filesystem::path('log'));

        $this->id = null;

        $author = $item->get_author();
        $article = array(
            'site_id' => $feed->site_id,
            'parent_id' => $feed->id,
            'guid' => $this->filterGuid($item->get_id()),
            'link' => $item->get_link(),
            'title' => $item->get_title(),
            'description' => $this->cleanupHtml($item),
            'pubdate' => gmdate('Y-m-d H:i:s', $item->get_date('U')),
            'author' => $author ? $author->get_name() : '',
            'format' => 'html'
        );

        try {
            $this->begin();

            $this->save($article);
            $images = $this->getArticleImages($item);
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

    protected function getArticleImages($item) {
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
            $medium = $enclosure->get_medium();
            if(!$medium || $medium == 'image') {
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

    protected function cleanupHtml($item) {
        $html = $item->get_content();
        $purifier = $this->getPurifier();
        $description = str_get_html($html);

        // PRODERJ only
        if(strpos($item->get_id(), 'www.rj.gov.br') !== false) {
            $description = implode(array_slice($description->find('p'), 1));
        }

        $paragraphs = $description->find('p');
        if(empty($paragraphs) && !empty($description)) {
            $description = '<p>' . $description . '</p>';
        }

        return $purifier->purify($description);
    }

    protected function filterGuid($guid) {
        $guid = preg_replace('%;jsessionid=[\w\d]+%', '', $guid);

        if(preg_match('%rj\.gov\.br%', $guid)) {
            $guid = preg_replace('%\.lportal.*articleId=%', '?articleId=', $guid);
        }

        return $guid;
    }
}
