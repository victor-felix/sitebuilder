<?php

require_once 'lib/simplepie/SimplePie.php';
require_once 'lib/geocoding/GoogleGeocoding.php';
require_once 'lib/sitemanager/SiteManager.php';

class Sites extends AppModel
{
	protected $getters = array('feed_url', 'feed_title', 'custom_domain');
	protected $beforeSave = array(
		'getLatLng', 'saveDomain', 'updateSiteManager',
	);
	protected $afterSave = array(
		'saveLogo', 'createNewsCategory', 'updateFeed',
		'saveDomains', 'createRelation'
	);
	protected $beforeDelete = array(
		'deleteImages',
		'deleteCategories',
		'deleteLogo',
		'removeUsers',
		'removeFromSiteManager'
	);
	protected $beforeValidate = array('checkForValidRss');
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
	);

	public function __construct($data = array())
	{
		parent::__construct($data);

		if (!isset($this->data['timezone']) or !$this->data['timezone']) {
			$this->timezone = 'America/Sao_Paulo';
		}
	}

	public function newsCategory()
	{
		return Model::load('Categories')->first(array(
			'conditions' => array('site_id' => $this->id, 'visibility' => -1)
		));
	}

	public function feed_url()
	{
		$category = $this->newsCategory();
		if ($category) return $category->feed_url;
	}

	public function feed_title()
	{
		$category = $this->newsCategory();
		if ($category) return $category->title;
	}

	public function defaultDomain()
	{
		return $this->slug . '.' . MeuMobi::domain();
	}

	public function custom_domain() {
		try {
			$domain = Model::load ( 'SitesDomains' )->first(array(
				'conditions' => array(
					'site_id' => $this->id,
					'domain !=' =>  $this->slug . '.' . MeuMobi::domain(),
				),
			));
			return $domain ? $domain->domain : null;
		} catch (Exception $e) {
			return null;
		}
	}

	public function firstByDomain($domain)
	{
		$sql = 'SELECT s.* FROM sites s
			INNER JOIN sites_domains d ON s.id = d.site_id
			WHERE d.domain = ?';
		$query = $this->connection()->query($sql, array($domain));
		$site = $query->fetch(PDO::FETCH_ASSOC);

		if ($site) return new Sites($site);
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

	public function country($id = false, $code = false) {
		$id = $id ? $id : $this->country_id;

		if (! $country = Model::load ( 'Countries' )->firstById ( ( int ) $id ))
			return '';

		return $code ? $country->tld : $country->name;
	}

	public function state($id = false) {
		$id = $id ? $id : $this->state_id;

		if (! $state = Model::load ( 'States' )->firstById ( ( int ) $id ))
			return '';

		return $state->name;
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
			'user_id' => Auth::user()->id(),
			'site_id' => $this->id,
			'segment' => MeuMobi::segment(),
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

	public function itemTypes()
	{
		return MeuMobi::currentSegment()->items;
	}

	public function hasManyTypes()
	{
		return is_array($this->itemTypes());
	}

	public function firstBySlug($slug) {
		$site = $this->first(array('conditions' => compact('slug')));
		if (!$site) throw new Exception('Missing slug');
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

	public function validateTheme()
	{
		return $this->theme && $this->skin;
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
		//check if has a default or custom domains
		if ((isset($data['slug']) && trim($data['slug']))
			|| isset($data['domains'])) {
			$defaultDomain = $data['slug'] . '.' . MeuMobi::domain();
			$data['domains'][] = $defaultDomain;
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
			return $created;
		}
		
		foreach ($domains as $id => $domain) {
			$previous = '';
			
			//check if is changing the domain value
			if ($siteDomain = Model::load('SitesDomains')->firstByIdAndSiteId($id, $this->id)) {
				$previous = $siteDomain->domain;
			} else {
				$siteDomain = new SitesDomains();
			}
			
			//check if domain exist in the site
			if ($domain && $domainExists = Model::load('SitesDomains')->check($domain)) {
				if ($domainExists->site_id != $this->id) {
					Session::writeFlash('error', s('The domain %s is not available', $domain));
					
					//delete if change the domain to a existent domain
				} else if ($previous && $siteDomain->id != $domainExists->id) {
					SiteManager::delete($siteDomain->domain);
					$siteDomain->delete($siteDomain->id);
				}
				continue;
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
		
		//set site domain field
		$defaultDomain = $this->data['slug'] . '.' . MeuMobi::domain();
		$custom = $this->custom_domain();
		$domain = $custom ? $custom : $defaultDomain;
		
		//update only if different
		if ($this->data['domain'] != $domain) {
			$this->update(array(
					'conditions' => array('id' => $this->id)
			), array(
					'domain' => $domain,
			));
		}
	}

	protected function getLatLng($data) {
		if (array_key_exists('street', $data)) {
			if ($this->id) {
				$original = $this->firstById($this->id);

				if ($original->street == $data['street']) {
					return $data;
				}
			}

			if (empty($data['street'])) {
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

	protected function createNewsCategory($created)
	{
		if ($created) {
			$parent_id = Model::load('Categories')->firstBySiteIdAndParentId($this->id, 0)->id;
			$category = new Categories(array(
				'site_id' => $this->id,
				'parent_id' => $parent_id,
				'type' => 'articles',
				'title' => 'News',
				'visibility' => - 1,
				'populate' => 'auto'
			));
			$category->save();
		}
	}

	protected function updateFeed($created)
	{
		if (isset($this->data['feed_url'])) {
			$category = $this->newsCategory();
			$category->updateAttributes(array(
				'title' => $this->data ['feed_title'],
				'feed_url' => $this->data ['feed_url']
			));
			$category->save();
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
		$this->deleteSet($model, $model->all(array(
			'conditions' => array('site_id' => $this->id, 'parent_id' => null)
		)));

		return $id;
	}

	protected function createRelation($created) {
		if ($created) {
			Model::load ( 'UsersSites' )->add ( Auth::user (), $this );
			Auth::user ()->site ( $this->id );
		}
	}

	protected function saveLogo()
	{
		if (array_key_exists('logo', $this->data) && !$this->data['logo']['error']) {
			if ($logo = $this->logo()) {
				Model::load('Images')->delete($logo->id);
			}

			Model::load('Images')->upload(new SiteLogos($this->id), $this->data['logo'], array('visible' => 1));
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

	protected function blacklist($value) {
		$blacklist = Config::read ( 'Sites.blacklist' );
		return ! in_array ( $value, $blacklist );
	}

	protected function checkForValidRss($data)
	{
		if (!trim($this->feed_url())) return true;
		$feed = new SimplePie();
		$feed->enable_cache(false);
		$feed->set_feed_url($this->feed_url());
		$feed->init();

		if ($feed->error()) {
			$this->errors['feed_url'] = $feed->error();
			return false;
		}

		return $data;
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
