<?php

class Sites extends AppModel {
    protected $beforeSave = array('getFeedId');
    protected $beforeDelete = array('checkAndDeleteFeed');
    
    public function feed() {
        if($this->feed_id) {
            return Model::load('Feeds')->firstById($this->feed_id);
        }
    }
    
    public function firstByDomain($domain) {
        $site = $this->first(array(
            'conditions' => compact('domain')
        ));
        
        if(!$site) throw new Exception('Missing domain');
        
        return $site;
    }

    protected function getFeedId($data) {
        if(array_key_exists('feed', $data)) {
            if(!empty($data['feed'])) {
                $link = $data['feed'];
                $feed = Model::load('Feeds')->saveFeed($link);
                $data['feed_id'] = $feed->id;
            }
            else {
                if($this->id) {
                    $this->checkAndDeleteFeed();
                }
                
                $data['feed_id'] = null;
            }
        }
        
        return $data;
    }
    
    protected function checkAndDeleteFeed($id) {
        $self = $this->firstById($id);
        if($self->feed_id) {
            $self->deleteFeedIfUnique();
        }
        
        return true;
    }
    
    protected function deleteFeedIfUnique() {
        $count = $this->count(array(
            'conditions' => array(
                'feed_id' => $this->feed_id
            )
        ));
        
        if($count == 1) {
            Model::load('Feeds')->delete($this->feed_id);
        }
    }
}