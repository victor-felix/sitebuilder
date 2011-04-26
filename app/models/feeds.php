<?php

require 'lib/simplepie/SimplePie.php';

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

        $this->updateAttributes(array(
            'updated' => date('Y-m-d H:i:s')
        ));
        $this->save();
    }

    public function saveFeed($site, $link) {
        $feed = $this->firstBySiteId($site->id);

        if(!empty($link)) {
            if(is_null($feed) || $feed->link != $link) {
                $this->save(array(
                    'site_id' => $site->id,
                    'link' => $link
                ));
                $this->updateArticles();
            }
        }

        if(!is_null($feed) && $link != $feed->link or empty($link)) {
            $this->delete($feed->id);
        }
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
