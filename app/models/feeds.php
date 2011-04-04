<?php

require 'lib/simplepie/SimplePie.php';
require 'lib/log/KLogger.php';

class Feeds extends AppModel {
    protected $beforeDelete = array('deleteArticles');

    public function updateArticles() {
        $log = KLogger::instance(Filesystem::path('log'));

        $articles = Model::load('Articles');
        $feed = $this->getFeed();
        $items = $feed->get_items();

        $log->logInfo('Importing feed "%s"', $this->link);
        $log->logInfo('%d articles found', count($items));

        foreach($items as $item) {
            if(!$articles->articleExists($this->id, $item->get_id())) {
                $articles->addToFeed($this, $item);
            }
            else {
                $log->logInfo('Article "%s" already exists. Skipping', $item->get_id());
            }
        }

        $this->save(array(
            'updated' => date('Y-m-d H:i:s')
        ));
    }

    public function saveFeed($link) {
        $feed = $this->firstByLink($link);
        if(is_null($feed)) {
            $this->save(array(
                'link' => $link
            ));
            $feed = $this->firstById($this->id);
        }
        $feed->updateArticles();

        return $feed;
    }

    public function topArticles() {
        return Model::load('Articles')->topByFeedId($this->id);
    }

    protected function getFeed() {
        $feed = new SimplePie();
        $feed->set_cache_location(FileSystem::path('tmp/cache/simplepie'));
        $feed->set_feed_url($this->link);
        $feed->init();

        return $feed;
    }

    protected function deleteArticles($id) {
        $model = Model::load('Articles');
        $items = $model->allByFeedId($id);
        $this->deleteSet($model, $items);

        return $id;
    }
}