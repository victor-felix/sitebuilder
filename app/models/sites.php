<?php

class Sites extends AppModel {
    protected $defaultScope = array(
        'orm' => true
    );
    protected $afterSave = array('saveFeed');
    
    public function saveFeed() {
        if(array_key_exists('feed', $this->data) && !empty($this->data['feed'])) {
            $link = $this->data['feed'];
            $feeds = Model::load('Feeds');
            $feed = $feeds->firstByLink($link);
            if(is_null($feed)) {
                $feeds->save(array(
                    'link' => $link
                ));
                $feed = $feeds->firstById($feeds->id);
                $feed->updateArticles();
            }
            $this->save(array(
                'feed_id' => $feed->id
            ));
        }
    }
    
    public function feed() {
        return Model::load('Feeds')->firstById($this->feed_id)->link;
    }
}