<?php

require 'lib/SimplePie.php';

class Feeds extends AppModel {
    public function updateArticles() {
        $articles = Model::load('Articles');
        $feed = new SimplePie();
        $feed->set_cache_location(FileSystem::path('tmp/cache/simplepie'));
        $feed->set_feed_url($this->link);
        $feed->init();
        
        $items = $feed->get_items();
        foreach($items as $item) {
            if(!$articles->articleExists($this->id, $item->get_id())) {
                $articles->addToFeed($this, $item);
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
}