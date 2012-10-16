<?php
require_once 'lib/simplepie/SimplePie.php';
require_once 'lib/geocoding/GoogleGeocoding.php';
require_once 'lib/sitemanager/SiteManager.php';

class Sites extends AppModel {
	protected $getters = array('feed_url', 'feed_title', 'custom_domain', );
	protected $beforeSave = array(
		'setHideCategories', 'getLatLng', 'saveDomain', 'updateSiteManager',
	);
	protected $afterSave = array(
		'saveLogo', 'createRootCategory', 'createNewsCategory', 'updateFeed','saveDomains',
		'createRelation'
	);
	protected $beforeDelete = array(
		'deleteImages', 
		'deleteCategories', 
		'deleteLogo',
		'removeUsers', 
		'removeFromSiteManager'
	);
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
			array(
				'rule' => array('fileUpload', 1, array('jpg', 'jpeg', 'gif', 'png')),
				'message' => 'Only valid gif, jpg, jpeg or png are allowed'
			),
			array(
				'rule' => array('validImage'),
				'message' => 'Only valid gif, jpg, jpeg or png are allowed'
			)
		),
		'description' => array(
			array(
				'rule' => array('maxLength', 500),
				'message' => 'The description of the site could contain 500 chars max.'
			)
		),
		'feed_url' => array(
			array(
				'rule' => 'isValidRss',
				'message' => 'The rss feed is invalid'
			),
		),
	);

	public function __construct($data = array()) {
		parent::__construct ( $data );

		if (! isset ( $this->data ['timezone'] ) or ! $this->data ['timezone']) {
			$this->timezone = 'America/Sao_Paulo';
		}
	}

	public function newsCategory() {
		return Model::load ( 'Categories' )->first ( array ('conditions' => array ('site_id' => $this->id, 'visibility' => - 1 ) ) );
	}

	public function feed_url() {
		$category = $this->newsCategory ();

		if ($category) {
			return $category->feed_url;
		}
	}

	public function feed_title() {
		$category = $this->newsCategory ();

		if ($category) {
			return $category->title;
		}
	}

	public function custom_domain() {
		return !empty($this->data['domain']) && strpos($this->domain, '.' . MeuMobi::domain()) === false;
	}
		
	public function firstByDomain($domain) {
		$siteDomain = Model::load ( 'SitesDomains' )->firstByDomain($domain);
		if ($siteDomain) {
			return self::firstById($siteDomain->site_id);
		}
	}
	
	public function domains() {
		$domains = array();
		if ($siteDomains = Model::load ( 'SitesDomains' )->allBySiteId ( $this->id )) {
			foreach ($siteDomains as $item) {
				$domains[$item->id] = $item->domain;
			}
		}
		return $domains;
	}
	
	public function photos() {
		return Model::load ( 'Images' )->allByRecord ( 'SitePhotos', $this->id );
	}

	public function photo() {
		return Model::load ( 'Images' )->firstByRecord ( 'SitePhotos', $this->id );
	}

	public function logo() {
		return Model::load ( 'Images' )->firstByRecord ( 'SiteLogos', $this->id );
	}

	public function link() {
		return 'http://' . $this->domain;
	}

	/**
	 * Get country name or code by country_id
	 *
	 * @param int $id The country id, optional.
	 * @param bollean $code if true return country code instead
	 * @return string
	 */
	public function country($id = false, $code = false) {
		$id = $id ? $id : $this->country_id;

		if (! $country = Model::load ( 'Countries' )->firstById ( ( int ) $id ))
			return '';

		return $code ? $country->tld : $country->name;
	}

	/**
	 * Get state name by state_id
	 *
	 * @param int $id The state id, optional
	 * @return string
	 */
	public function state($id = false) {
		$id = $id ? $id : $this->state_id;

		if (! $state = Model::load ( 'States' )->firstById ( ( int ) $id ))
			return '';

		return $state->name;
	}

	public function rootCategory() {
		return Model::load ( 'Categories' )->getRoot ( $this->id );
	}

	public function categories() {
		return Model::load('Categories')->all(array(
				'conditions' => array ('site_id' => $this->id, 'visibility >' => - 1),
				'order' => '`order`'
				));
	}

	public function userRole() {
		try {
			if ($this->role) {
				return $this->role;
			}
		} catch(Exception $e) {

		}

		$model = Model::load('UsersSites')->first(array(
			'user_id'		=> Auth::user()->id(),
			'site_id'		=> $this->id,
			'segment'	=> MeuMobi::segment(),
		));

		if ($model) {
			$this->role = $model->role;
		}
		return $this->role;
	}


	public function users($removeCurrent = false) {
		$usersIds = Model::load('UsersSites')->getAllUsers($this);
		$users = Model::load('Users')->allById($usersIds);

		if ($removeCurrent) {
			$current = Auth::user();
			foreach ( $users as $key => $user ) {
				if ($current->id == $user->id) {
					unset($users[$key]);
				}
			}
		}

		return $users;
	}

	public function removeUser($userId) {
		if($user = Model::load('Users')->firstById($userId)) {
			return Model::load('UsersSites')->remove($user, $this);
		}
	}

	public function itemTypes() {
		return Model::load ( 'Segments' )->firstById ( $this->segment )->items;
	}

	public function hasManyTypes() {
		return is_array ( $this->itemTypes () );
	}

	public function firstBySlug($slug) {
		$site = $this->first ( array ('conditions' => compact ( 'slug' ) ) );

		if (! $site)
			throw new Exception ( 'Missing slug' );

		return $site;
	}

	public function dateFormats() {
		return array ('d/m/Y' => 'DD/MM/YYYY', 'm/d/Y' => 'MM/DD/YYYY', 'Y-m-d' => 'YYYY-MM-DD' );
	}

	public function timezones() {
		$timezones = DateTimeZone::listIdentifiers ();
		$options = array ();

		foreach ( $timezones as $tz ) {
			$options [$tz] = str_replace ( '_', ' ', $tz );
		}

		return $options;
	}

	public function timezone() {
		$tz_site = new DateTimeZone ( $this->timezone );
		$tz_server = new DateTimeZone ( date_default_timezone_get () );
		$time_site = new DateTime ( 'now', $tz_site );
		$time_server = new DateTime ( 'now', $tz_server );

		return $tz_server->getOffset ( $time_site ) / 3600;
	}

	public function toJSON() {
		$data = array_merge ( $this->data, array ('logo' => null, 'photos' => array (), 'timezone' => $this->timezone () ) );

		if ($logo = $this->logo ()) {
			$data ['logo'] = $logo->link ();
		}

		$photos = $this->photos ();
		foreach ( $photos as $photo ) {
			$data ['photos'] [] = $photo->toJSON ();
		}

		if ($this->country_id) {
			$country = Model::load ( 'Countries' )->firstById ( $this->country_id )->name;
			$data ['country'] = $country;
		} else {
			$data ['country'] = '';
		}

		if ($this->state_id) {
			$state = Model::load ( 'States' )->firstById ( $this->state_id )->name;
			$data ['state'] = $state;
		} else {
			$data ['state'] = '';
		}

		$data ['description'] = nl2br ( $data ['description'] );

		return $data;
	}

	protected function removeUsers($id) {
		Model::load ( 'UsersSites' )->onDeleteSite ( $this );
		return $id;
	}

	protected function removeFromSiteManager($id)
	{
		foreach ($this->domains() as $domain) {
			SiteManager::delete($domain);
		}
		return $id;
	}

	protected function saveDomain($data)
	{
		$siteId = isset($data['id']) ? $data['id'] : null;
		//check if use a default or custon domain
		if (isset($data['custom_domain'])) {
			if ($data['custom_domain'] 
				&& (isset($data['domains']) && reset($data['domains']))) {
				//add the first custon domain to the domain field
				$domain = reset($data['domains']);
			} else {
				$domain = $data['slug'] . '.' . MeuMobi::domain();
				$data['domains'] = (array)$domain;
			}
			//check if domain already exists
			if ($exists = Model::load('SitesDomains')->check($domain)) {
				if ($exists->site_id != $siteId ) {
					throw new RuntimeException("The {$domain} is not available");
				}
			}
			$data['domain'] = $domain;
		}
		
		return $data;
	}
	
	protected function saveDomains($created) 
	{		
		$instance = MeuMobi::instance();
		//handle default error if domains not exists
		try {
			$domains = $this->domains;
		} catch (Exception $e) {
			$domains = array();
		}
		foreach ($domains as $id => $domain) {
			$previous = '';
			//check if domain exist in the site
			if ($domain && $domainExists = Model::load('SitesDomains')->check($domain)) {
				if ($domainExists->site_id != $this->id) {
					throw new RuntimeException("The {$domain} is not available");
				}
				continue;
			}
			//check if is changing the domain value
			if ($siteDomain = Model::load('SitesDomains')->firstByIdAndSiteId($id, $this->id)) {
				$previous = $siteDomain->domain;
			} else {
				$siteDomain = new SitesDomains();
			}
			
			//if old domain is empty, removes it
			if (!$domain) {
				if ($previous) {
					SiteManager::delete($siteDomain->domain);
					$siteDomain->delete($siteDomain->id);
				}
				continue;
			}
			
			$siteDomain->domain = $domain;
			$siteDomain->site_id = $this->id;
			if ($siteDomain->validate()) {
				$siteDomain->save();
				if ($previous) {
					SiteManager::update($previous, $domain, $instance);
				} else {
					SiteManager::create($domain, $instance);
				}
			}
		}
	}
	protected function setHideCategories($data) {
		$segment = Model::load ( 'Segments' )->firstById ( MeuMobi::segment () );
		$data ['hide_categories'] = $segment->hideCategories;
		return $data;
	}

	protected function getLatLng($data) {
		if (array_key_exists ( 'street', $data )) {
			if (empty ( $data ['street'] )) {
				$data ['latitude'] = $data ['longitude'] = null;
			} else {
				try {
					$address = String::insert ( ':street, :number, :city - :state, :country', array ('street' => $data ['street'], 'number' => $data ['number'], 'city' => $data ['city'], 'state' => $this->state ( $data ['state_id'] ), 'country' => $this->country ( $data ['country_id'] ) ) );

					$region = $this->country ( $data ['country_id'], true );

					$geocode = GoogleGeocoding::geocode ( $address, $region );
					$location = $geocode->results [0]->geometry->location;
					$data ['latitude'] = $location->lat;
					$data ['longitude'] = $location->lng;
				} catch ( Exception $e ) {
					$data ['latitude'] = $data ['longitude'] = null;
				}
			}
		}

		return $data;
	}

	protected function createNewsCategory($created) {
		if ($created) {
			$parent_id = Model::load ( 'Categories' )->firstBySiteIdAndParentId ( $this->id, 0 )->id;
			$category = new Categories ( array ('site_id' => $this->id, 'parent_id' => $parent_id, 'type' => 'articles', 'title' => 'News', 'visibility' => - 1, 'populate' => 'auto' ) );
			$category->save ();
		}
	}
	protected function updateFeed($created) {
		if (isset ( $this->data ['feed_url'] )) {
			$category = $this->newsCategory ();
			$category->updateAttributes ( array ('title' => $this->data ['feed_title'], 'feed' => $this->data ['feed_url'] ) );
			$category->save ();
		}
	}

	protected function updateSiteManager($data)
	{
		return $data;
	}

	protected function deleteLogo($id) {
		$model = Model::load ( 'Images' );
		$images = $model->allByRecord ( 'SiteLogos', $id );
		$this->deleteSet ( $model, $images );

		return $id;
	}

	protected function deleteCategories($id) {
		$model = Model::load ( 'Categories' );
		$root = $model->getRoot ( $id );
		$model->forceDelete ( $root->id );

		return $id;
	}

	protected function createRelation($created) {
		if ($created) {
			Model::load ( 'UsersSites' )->add ( Auth::user (), $this );
			Auth::user ()->site ( $this->id );
		}
	}

	protected function saveLogo() {
		if (array_key_exists ( 'logo', $this->data ) && $this->data ['logo'] ['error'] == 0) {
			if ($logo = $this->logo ()) {
				Model::load ( 'Images' )->delete ( $logo->id );
			}

			Model::load ( 'Images' )->upload ( new SiteLogos ( $this->id ), $this->data ['logo'], array('visible' => 1) );
		}
	}

	protected function savePhoto() {
		if (array_key_exists ( 'photo', $this->data )) {
			foreach ( $this->data ['photo'] as $photo ) {
				if ($photo ['error'] == 0) {
					Model::load ( 'Images' )->upload ( new SitePhotos ( $this->id ), $photo );
				}
			}
		}
	}

	protected function createRootCategory($created) {
		if ($created) {
			Model::load ( 'Categories' )->createRoot ( $this );
		}
	}

	protected function blacklist($value) {
		$blacklist = Config::read ( 'Sites.blacklist' );
		return ! in_array ( $value, $blacklist );
	}
	
	protected function isValidRss($value)
	{
		if (!trim($value)) {
			return true;
		}
		$feed = new SimplePie();
		$feed->enable_cache(false);
		$feed->set_feed_url($value);
		$feed->init();
		
		return !$feed->error();
	}
}

class SiteLogos {
	public $id;

	public function __construct($id = null) {
		$this->id = $id;
	}

	public function resizes() {
		$config = Config::read ( 'SiteLogos.resizes' );
		if (is_null ( $config )) {
			$config = array ();
		}

		return $config;
	}

	public function imageModel() {
		return 'SiteLogos';
	}

	public function id() {
		return $this->id;
	}
}

class SitePhotos {
	public $id;

	public function __construct($id = null) {
		$this->id = $id;
	}

	public function resizes() {
		$config = Config::read ( 'SitePhotos.resizes' );
		if (is_null ( $config )) {
			$config = array ();
		}

		return $config;
	}

	public function imageModel() {
		return 'SitePhotos';
	}

	public function firstById($id) {
		return new self ( $id );
	}

	public function id() {
		return $this->id;
	}
}
