<?php

class Sites extends AppModel {
    protected $beforeSave = array('getFeedId');
    protected $afterSave = array('saveLogo');
    protected $beforeDelete = array('checkAndDeleteFeed');
    protected $validates = array(
        'domain' => array(
            array(
                'rule' => array('unique', 'domain'),
                'on' => 'create',
                'message' => 'O domínio já foi escolhido'
            ),
            array(
                'rule' => 'asciiOnly',
                'on' => 'create',
                'message' => 'O domínio só pode conter caracteres minúsculos, hifens e underscores',
            ),
            array(
                'rule' => 'subdomain',
                'on' => 'create',
                'message' => 'O domínio só pode conter caracteres minúsculos, hifens e underscores',
            )
        ),
        'segment' => array(
            'rule' => 'notEmpty',
            'on' => 'create',
            'message' => 'Você precisa selecionar um segmento'
        ),
        'theme' => array(
            'rule' => 'notEmpty',
            'on' => 'create',
            'message' => 'Você precisa selecionar um tema'
        ),
        'title' => array(
            'rule' => 'notEmpty',
            'message' => 'Você precisa definir um título'
        )
    );
    
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
    
    protected function saveLogo() {
        // if(valid) {
        Model::load('Images')->upload($this, $this->data['logo'], 'images/:model/:id.:ext');
        // }
    }
    
    // only allows domains in meumobi.com - may change in the future
    protected function subdomain($value) {
        return preg_match('/[\w_-]+.meumobi.com$/', $value);
    }
}