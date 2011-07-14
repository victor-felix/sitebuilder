<?php

require_once 'lib/geocoding/GoogleGeocoding.php';

class Sites extends AppModel {
    protected $getters = array('feed_url', 'feed_title', 'custom_domain');
    protected $beforeSave = array('getLatLng', 'saveCustomDomain');
    protected $afterSave = array('saveLogo', 'savePhoto', 'createRootCategory',
        'createNewsCategory', 'updateFeed');
    protected $beforeDelete = array('checkAndDeleteFeed', 'deleteImages', 'deleteCategories',
        'deleteLogo');
    protected $validates = array(
        'slug' => array(
            array(
                'rule' => array('unique', 'slug'),
                'message' => 'This domain is not available'
            ),
            array(
                'rule' => 'asciiOnly',
                'message' => 'The domain can only contains lowercase, dashes and underscores'
            ),
            array(
                'rule' => 'blacklist',
                'message' => 'This domain is not available'
            )
        ),
        'title' => array(
            'rule' => 'notEmpty',
            'message' => 'A non empty title is required'
        ),
        'logo' => array(
            'rule' => array('fileUpload', 1, array('jpg', 'gif', 'png')),
            'message' => 'Only valid gif, jpg or png are allowed',
        ),
        'description' => array(
            array(
                'rule' => array('maxLength', 500),
                'message' => 'The description of the site could contain 500 chars max.'
            )
        ),
    );

    public function __construct($data = array()) {
        parent::__construct($data);

        if(!isset($this->data['timezone']) or !$this->data['timezone']) {
            $this->timezone = 'America/Sao_Paulo';
        }
    }

    public function newsCategory() {
        return Model::load('Categories')->first(array(
            'conditions' => array(
                'site_id' => $this->id,
                'visibility' => -1
            )
        ));
    }

    public function feed_url() {
        $category = $this->newsCategory();

        if($category) {
            return $category->feed_url;
        }
    }

    public function feed_title() {
        $category = $this->newsCategory();

        if($category) {
            return $category->title;
        }
    }

    public function custom_domain() {
        return !empty($this->domain) && strpos($this->domain, '.meumobi.com') === false;
    }

    public function photos() {
        return Model::load('Images')->allByRecord('SitePhotos', $this->id);
    }

    public function photo() {
        return Model::load('Images')->firstByRecord('SitePhotos', $this->id);
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
        return Model::load('Categories')->all(array(
            'conditions' => array(
                'site_id' => $this->id,
                'visibility >' => -1
            )
        ));
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

    public function dateFormats() {
        return array(
            'd/m/Y' => 'DD/MM/YYYY',
            'm/d/Y' => 'MM/DD/YYYY',
            'Y-m-d' => 'YYYY-MM-DD'
        );
    }

    public function timezones() {
        $timezones = DateTimeZone::listIdentifiers();
        $options = array();

        foreach($timezones as $tz) {
            $options[$tz] = str_replace('_', ' ', $tz);
        }

        return $options;
    }

    public function timezone() {
        $tz_site = new DateTimeZone($this->timezone);
        $tz_server = new DateTimeZone(date_default_timezone_get());
        $time_site = new DateTime('now', $tz_site);
        $time_server = new DateTime('now', $tz_server);

        return $tz_server->getOffset($time_site) / 3600;
    }

    public function toJSON() {
        $data = array_merge($this->data, array(
            'logo' => null,
            'photos' => array(),
            'timezone' => $this->timezone()
        ));

        if($logo = $this->logo()) {
            $data['logo'] = $logo->link();
        }

        if($photo = $this->photo()) {
            $data['photos'] []= $photo->toJSON();
        }

        if($this->country_id) {
            $country = Model::load('Countries')->firstById($this->country_id)->name;
            $data['country'] = $country;
        }
        else {
            $data['country'] = '';
        }

        if($this->state_id) {
            $state = Model::load('States')->firstById($this->state_id)->name;
            $data['state'] = $state;
        }
        else {
            $data['state'] = '';
        }

        $data['description'] = nl2br($data['description']);

        return $data;
    }

    protected function saveCustomDomain($data) {
        if(isset($data['custom_domain']) && (!$data['custom_domain'] || empty($data['domain']))) {
            $data['domain'] = $data['slug'] . '.meumobi.com';
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
                    $location = $geocode->results[0]->geometry->location;
                    $data['latitude'] = $location->lat;
                    $data['longitude'] = $location->lng;
                }
                catch(Exception $e) {
                    $data['latitude'] = $data['longitude'] = null;
                }
            }
        }

        return $data;
    }

    protected function createNewsCategory($created) {
        if($created) {
            $parent_id = Model::load('Categories')->firstBySiteIdAndParentId(
                $this->id, 0
            )->id;
            $category = new Categories(array(
                'site_id' => $this->id,
                'parent_id' => $parent_id,
                'type' => 'articles',
                'title' => 'News',
                'visibility' => -1,
                'populate' => 'auto'
            ));
            $category->save();
        }
    }
    protected function updateFeed($created) {
        if(isset($this->data['feed_url'])) {
            $category = $this->newsCategory();
            $category->updateAttributes(array(
                'title' => $this->data['feed_title'],
                'feed' => $this->data['feed_url']
            ));
            $category->save();
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

    protected function savePhoto() {
        if(array_key_exists('photo', $this->data) && $this->data['photo']['error'] == 0) {
            Model::load('Images')->upload(new SitePhotos($this->id), $this->data['photo']);
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

    public function imageModel() {
        return 'SiteLogos';
    }
}

class SitePhotos {
    public $id;

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function resizes() {
        $config = Config::read('SitePhotos.resizes');
        if(is_null($config)) {
            $config = array();
        }

        return $config;
    }

    public function imageModel() {
        return 'SitePhotos';
    }
}
