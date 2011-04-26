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
    protected static $blacklist = array(
        'gravatar.com'
    );

    public function fields() {
        return array('title', 'description', 'author');
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
            'feed_id' => $feed->id,
            'guid' => $this->filterGuid($item->get_id()),
            'link' => $item->get_link(),
            'title' => $item->get_title(),
            'description' => $this->cleanupHtml($item->get_content()),
            'author' => $author ? $author->get_name() : '',
            'pubdate' => $item->get_date('Y-m-d H:i:s')
        );

        $this->save($article);
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
