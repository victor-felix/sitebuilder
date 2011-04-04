<?php

require_once 'lib/geocoding/GoogleGeocoding.php';

class Sites extends AppModel {
    protected $getters = array('feed_url');
    protected $beforeSave = array('getFeedId', 'getLatLng');
    protected $afterSave = array('saveLogo', 'createRootCategory');
    protected $beforeDelete = array('checkAndDeleteFeed', 'deleteImages', 'deleteCategories',
        'deleteLogo');
    protected $validates = array(
        'slug' => array(
            array(
                'rule' => array('unique', 'slug'),
                'message' => 'O domínio já foi escolhido'
            ),
            array(
                'rule' => 'asciiOnly',
                'message' => 'O domínio só pode conter caracteres minúsculos, hifens e underscores'
            )
        ),
        'title' => array(
            'rule' => 'notEmpty',
            'message' => 'Você precisa definir um título'
        ),
        'logo' => array(
            'rule' => array('fileUpload', 1, array('jpg', 'gif', 'png')),
            'message' => 'Você precisa usar uma imagem válida',
        ),
        'description' => array(
            array(
                'rule' => array('maxLength', 500),
                'message' => 'A descrição do site não pode conter mais do que 500 caracteres'
            )            
        ),
    );
    
    public function feed() {
        if($this->feed_id) {
            return Model::load('Feeds')->firstById($this->feed_id);
        }
    }

    public function topArticles() {
        if($this->feed_id) {
            return $this->feed()->topArticles();
        }
        else {
            return array();
        }
    }
    
    public function feed_url() {
        $feed = $this->feed();
        if($feed) {
            return $feed->link;
        }
    }
    
    public function logo() {
        return Model::load('Images')->firstByRecord('SiteLogos', $this->id);
    }
    
    public function link() {
        return 'http://' . $this->slug . '.meumobi.com';
    }
    
    public function rootCategory() {
        return Model::load('Categories')->getRoot($this->id);
    }

    public function categories() {
        return Model::load('Categories')->allBySiteId($this->id);
    }
    
    public function businessItems($conditions = array()) {
        return Model::load('BusinessItems')->all(array(
            'conditions' => array(
                'site_id' => $this->id
            ) + $conditions
        ));
    }

    public function businessItemTypeName() {
        return Model::load('Segments')->firstById($this->segment)->business_item;
    }
    
    public function businessItemType() {
        return Model::load('BusinessItemsTypes')->firstById($this->businessItemTypeName());
    }
    
    public function firstBySlug($slug) {
        $site = $this->first(array(
            'conditions' => compact('slug')
        ));
        
        if(!$site) throw new Exception('Missing slug');
        
        return $site;
    }

    public function toJSON() {
        $data = array_merge($this->data, array(
            'logo' => null
        ));

        if($logo = $this->logo()) {
            $data['logo'] = $logo->link();
        }

        return $data;
    }

    protected function getLatLng($data) {
        if(array_key_exists('street', $data)) {
            if(empty($data['street'])) {
                $data['latitude'] = $data['longitude'] = null;
            }
            else {
                try {
                    $address = String::insert(':street, :number, :city - :state, :country', array(
                        'street' => $data['street'],
                        'number' => $data['number'],
                        'city' => $data['city'],
                        'state' => $data['state'],
                        'country' => $data['country']
                    ));
                    $geocode = GoogleGeocoding::geocode($address);
                    $data['latitude'] = $geocode->results[0]->geometry->location->lat;
                    $data['longitude'] = $geocode->results[0]->geometry->location->lng;
                }
                catch(Exception $e) {
                    $data['latitude'] = $data['longitude'] = null;
                }
            }
        }
        
        return $data;
    }

    protected function getFeedId($data) {
        if(array_key_exists('feed_url', $data)) {
            if(!empty($data['feed_url'])) {
                $link = $data['feed_url'];
                $feed = Model::load('Feeds')->saveFeed($link);
                $data['feed_id'] = $feed->id;
            }
            else {
                if($this->id) {
                    $this->checkAndDeleteFeed($this->id);
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
        
        return $id;
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

    protected function deleteLogo($id) {
        $model = Model::load('Images');
        $images = $model->allByRecord('SiteLogos', $id);
        $this->deleteSet($model, $images);

        return $id;
    }

    protected function deleteCategories($id) {
        $model = Model::load('Categories');
        $root = $model->getRoot($id);
        $model->forceDelete($root->id);

        return $id;
    }
    
    protected function saveLogo() {
        if(array_key_exists('logo', $this->data) && $this->data['logo']['error'] == 0) {
            if($logo = $this->logo()) {
                Model::load('Images')->delete($logo->id);
            }
            
            Model::load('Images')->upload(new SiteLogos($this->id), $this->data['logo']);
        }
    }
    
    protected function createRootCategory($created) {
        if($created) {
            Model::load('Categories')->createRoot($this);
        }
    }
}

class SiteLogos {
    public $id;
    
    public function __construct($id = null) {
        $this->id = $id;
    }
    
    public function resizes() {
        $config = Config::read('SiteLogos.resizes');
        if(is_null($config)) {
            $config = array();
        }
        
        return $config;
    }
}
