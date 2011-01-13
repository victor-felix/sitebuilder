<?php

require 'lib/SimplePie.php';

class Feeds extends AppModel {
    public function updateArticles() {
        $feed = new SimplePie();
        $feed->set_cache_location(FileSystem::path('tmp/cache/simplepie'));
        $feed->set_feed_url($this->link);
        $feed->init();
    }
}