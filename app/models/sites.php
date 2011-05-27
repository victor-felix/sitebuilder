<?php

require_once 'lib/geocoding/GoogleGeocoding.php';

class Sites extends AppModel {
    protected $getters = array('feed_url');
    protected $beforeSave = array('getLatLng');
    protected $afterSave = array('saveFeed', 'saveLogo', 'createRootCategory');
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
            ),
            array(
                'rule' => 'blacklist',
                'message' => 'O domínio escolhido não pode ser utilizado'
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
        return Model::load('Feeds')->firstBySiteId($this->id);
    }

    public function topArticles() {
        $feed = $this->feed();
        if(!is_null($feed)) {
            return $feed->topArticles();
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
        $domain = MeuMobi::domain();
        return 'http://' . $this->slug . '.' . $domain;
    }

    public function rootCategory() {
        return Model::load('Categories')->getRoot($this->id);
    }

    public function categories() {
        return Model::load('Categories')->allBySiteId($this->id);
    }

    public function businessItems($type, $conditions, $params) {
        return Model::load($type)->allOrdered(array(
            'conditions' => array(
                'site_id' => $this->id
            ) + $conditions
        ) + $params);
    }

    public function itemTypes() {
        return Model::load('Segments')->firstById($this->segment)->items;
    }

    public function hasManyTypes() {
        return is_array($this->itemTypes());
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

        $data['description'] = nl2br($data['description']);

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

    protected function saveFeed($created) {
        if(array_key_exists('feed_url', $this->data)) {
            Model::load('Feeds')->saveFeed($this, $this->data['feed_url']);
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

    protected function blacklist($value) {
        $blacklist = Config::read('Sites.blacklist');
        return !in_array($value, $blacklist);
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
