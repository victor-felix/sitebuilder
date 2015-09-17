<?php

require_once 'app/models/categories.php';
require_once 'lib/geocoding/GoogleGeocoding.php';

use app\models\Plugins;
use app\models\extensions\Rss;
use app\models\items\Articles;
use meumobi\sitebuilder\entities\Skin;
use meumobi\sitebuilder\repositories\SkinsRepository;
use meumobi\sitebuilder\repositories\VisitorsRepository;

class Sites extends AppModel
{
	protected $getters = [
		'feed_url',
		'feed_title',
		'domain',
		'domains',
	];

	protected $beforeSave = [
		'getLatLng',
		'addSlugDomain',
		'trimFields',
		'cleanDomainLinks',
	];

	protected $afterSave = [
		'saveLogoAndAppleTouchIcon',
		'updateFeed',
		'saveDomains',
		'createRelation',
	];

	protected $validates = [
		'slug' => [
			[
				'rule' => ['unique', 'slug'],
				'message' => 'This domain is not available'
			],
			[
				'rule' => 'asciiOnly',
				'message' => 'The domain can only contains lowercase, dashes and underscores'
			],
			[
				'rule' => 'blacklist',
				'message' => 'This domain is not available'
			]
		],
		'title' => [
			'rule' => 'notEmpty',
			'message' => 'A non empty title is required'
		],
		'logo' => [
			[
				'rule' => ['fileUpload', 1, ['jpg', 'jpeg', 'gif', 'png']],
				'message' => 'Only valid gif, jpg, jpeg or png are allowed'
			],
			[
				'rule' => ['validImage'],
				'message' => 'Only valid gif, jpg, jpeg or png are allowed'
			]
		],
		'description' => [
			[
				'rule' => ['maxLength', 500],
				'message' => 'The description of the site could contain 500 chars max.'
			]
		],
	];

	protected $categories;
	protected $plugins;

	public function __construct($data = [])
	{
		parent::__construct($data);

		if (!isset($this->data['timezone']) or !$this->data['timezone']) {
			$this->timezone = 'America/Sao_Paulo';
		}
	}

	public function newsCategory()
	{
		return Model::load('Categories')->first([
			'conditions' => [
				'site_id' => $this->id,
				'visibility' => -1,
			]
		]);
	}

	public function newsExtension()
	{
		$category = $this->newsCategory();
		if ($category)
		return Rss::find('first', [
			'conditions' => [
				'category_id' => $category->id,
			]
		]);
	}

	public function news()
	{
		$category = $this->newsCategory();
		if (!$category)
			return [];
		return Articles::find('all', [
			'conditions' => [
				'site_id' => $this->id,
				'parent_id' => $category->id,
			],
			'limit' => 10,
			'order' => [
				'published' => 'DESC',
			],
		])->to('array');
	}

	public function feed_url()
	{
		if ($extension = $this->newsExtension()) {
			return $extension->url;
		}
	}

	public function feed_title()
	{
		if ($category = $this->newsCategory()) {
			return $category->title;
		}
	}

	public function domain()
	{
		try {
			$domain = Model::load('SitesDomains')->first([
				'conditions' => [
					'site_id' => $this->id,
				],
				'order' => '`id` DESC',
			]);

			return $domain ? $domain->domain : null;
		} catch (Exception $e) {
			return null;
		}
	}

	public function domains()
	{
		$domains = [];
		if ($siteDomains = Model::load('SitesDomains')->allBySiteId($this->id)) {
			foreach ($siteDomains as $item) {
				$domains[$item->id] = $item->domain;
			}
		}

		return $domains;
	}

	public function firstByDomain($domain)
	{
		$sql = 'SELECT s.* FROM sites s
			INNER JOIN sites_domains d ON s.id = d.site_id
			WHERE d.domain = ?';

		$query = $this->connection()->query($sql, [$domain]);
		$site = $query->fetch(PDO::FETCH_ASSOC);

		return $site ? new Sites($site) : null;
	}

	public function photos()
	{
		return Model::load('Images')->allByRecord('SitePhotos', $this->id);
	}

	public function photo()
	{
		return Model::load('Images')->firstByRecord('SitePhotos', $this->id);
	}

	public function appleTouchIcon()
	{
		return Model::load('Images')->firstByRecord('SiteAppleTouchIcon', $this->id);
	}

	public function availableVisitorsGroups()
	{
		$repository = new VisitorsRepository();
		return $repository->findAvailableGroupsBySite($this->id);
	}

	public function splashScreen()
	{
		return Model::load('Images')->firstByRecord('SiteSplashScreens', $this->id);
	}

	public function logo()
	{
		return Model::load('Images')->firstByRecord('SiteLogos', $this->id);
	}

	public function link()
	{
		return 'http://' . $this->domain();
	}

	public function skin()
	{
		if ($this->skin) {
			$skinRepo = new SkinsRepository();
			return $skinRepo->find($this->skin);
		}
	}

	public function plugins()
	{
		if ($this->plugins) return $this->plugins;

		return $this->plugins = Plugins::find('all', [
			'conditions' => [
				'site_id' => $this->id,
			]
		]);
	}

	public function categories()
	{
		if ($this->categories) return $this->categories;

		return $this->categories = Model::load('Categories')->all([
			'conditions' => [
				'site_id' => $this->id,
				'visibility >' => -1,
			],
			'order' => '`order`',
		]);
	}

	public function visibleCategories()
	{
		return Model::load('Categories')->all([
			'conditions' => [
				'site_id' => $this->id,
				'visibility' => 1,
			],
			'order' => '`order`',
		]);
	}

	public function userRole()
	{
		try {
			if ($this->role) {
				return $this->role;
			}
		} catch(Exception $e) {

		}

		$model = Model::load('UsersSites')->first([
			'user_id' => Auth::user()->id(),
			'site_id' => $this->id,
			'segment' => MeuMobi::segment(),
		]);

		if ($model) {
			$this->role = $model->role;
		}
		return $this->role;
	}


	public function users($removeCurrent = false)
	{
		$usersIds = Model::load('UsersSites')->getAllUsers($this);
		$users = Model::load('Users')->allById($usersIds);

		if ($removeCurrent) {
			$current = Auth::user();
			foreach ($users as $key => $user) {
				if ($current->id == $user->id) {
					unset($users[$key]);
				}
			}
		}

		return $users;
	}

	public function removeUser($userId)
	{
		if ($user = Model::load('Users')->firstById($userId)) {
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

	public function dateFormats()
	{
		return [
			'd/m/Y' => 'DD/MM/YYYY',
			'm/d/Y' => 'MM/DD/YYYY',
			'Y-m-d' => 'YYYY-MM-DD',
		];
	}

	public function timezones()
	{
		$timezones = DateTimeZone::listIdentifiers();
		$options = [];

		foreach ($timezones as $tz) {
			$options [$tz] = str_replace('_', ' ', $tz);
		}

		return $options;
	}

	public function timezoneId() {
		if (!empty($this->timezone)) {
			return $this->timezone;
		} else {
			return 'America/Sao_Paulo';
		}
	}

	public function timezone()
	{
		$tz_site = new DateTimeZone($this->timezone);
		$tz_server = new DateTimeZone(date_default_timezone_get());
		$time_site = new DateTime('now', $tz_site);
		$time_server = new DateTime('now', $tz_server);

		return $tz_server->getOffset($time_site) / 3600;
	}

	public function validateTheme()
	{
		return $this->theme && $this->skin;
	}

	public function toJSON()
	{
		$data = array_merge($this->data, [
			'logo' => null,
			'apple_touch_icon' => null,
			'photos' => [],
			'timezone' => $this->timezone(),
		]);

		unset($data['private']); //remove private attr

		if ($logo = $this->logo()) {
			$data['logo'] = $logo->link();
		}

		if ($appleTouchIcon = $this->appleTouchIcon()) {
			$data['apple_touch_icon'] = $appleTouchIcon->link();
		}

		$photos = $this->photos();
		foreach ($photos as $photo) {
			$data['photos'] []= $photo->toJSON();
		}

		$data['description'] = nl2br($data['description']);
		$data['address'] = nl2br($data['address']);
		$data['timetable'] = nl2br($data['timetable']);

		return $data;
	}

	public function addDefaultPhotos()
	{
		$imagesDir = APP_ROOT . '/sitebuilder/assets/images/site_placeholders/';
		$images = glob($imagesDir . '{*.jpg,*.gif,*.png}', GLOB_BRACE);
		foreach ($images as $img) {
			$img = Mapper::url('/images/shared/site_placeholders/' . basename($img), true);
			$image = Model::load('Images')->download(new SitePhotos($this->id), $img, [
				'visible' => 1,
				'description' => 'edit legend',
			]);
		}
	}

	protected function removeUsers($id)
	{
		Model::load('UsersSites')->removeSite($id);

		return $id;
	}

	protected function trimFields($data)
	{
		$fieldsToTrim = ['description', 'timetable', 'address', 'email', 'phone',
										'website', 'google_analytics', 'css_token', 'facebook',
										'twitter'];

		foreach ($fieldsToTrim as $field) {
			if (isset($data[$field])) {
				$data[$field] = trim($data[$field]);
			}
		}

		return $data;
	}

	protected function cleanDomainLinks($data)
	{
		$fieldsToClean = ['facebook', 'twitter', 'website',
											'android_app_id', 'ios_app_id'];

		foreach ($fieldsToClean as $field) {
			if (isset($data[$field]) && $data[$field] == 'http://') {
				$data[$field] = '';
			}
		}

		return $data;
	}

	protected function addSlugDomain($data)
	{
		$new = !isset($data['id']);

		if ($new) {
			$data['domains'][] = $data['slug'] . '.' . MeuMobi::domain();
		}

		return $data;
	}

	protected function saveDomains($created)
	{
		try {
			$domains = $this->domains;
		} catch (Exception $e) {
			return $created;
		}

		foreach ($domains as $id => $domain) {
			$siteDomain = Model::load('SitesDomains')->firstByIdAndSiteId($id, $this->id);

			if (!$siteDomain) {
				$siteDomain = new SitesDomains();
			}

			$siteDomain->domain = $domain;
			$siteDomain->site_id = $this->id;

			if ($siteDomain->validate()) {
				$siteDomain->save();
			} else {
				Session::writeFlash('error', s('The domain %s is not available', $domain));
			}
		}

		return $created;
	}

	protected function getLatLng($data)
	{
		if (array_key_exists('address', $data)) {
			if ($this->id) {
				$original = $this->firstById($this->id);

				if ($original->address == $data['address']) {
					return $data;
				}
			}

			if (empty($data['address'])) {
				$data['latitude'] = $data['longitude'] = null;
			} else {
				try {
					$geocode = GoogleGeocoding::geocode($data['address']);
					$location = $geocode->results[0]->geometry->location;
					$data['latitude'] = $location->lat;
					$data['longitude'] = $location->lng;
				} catch (GeocodingException $e) {
					$data['latitude'] = $data['longitude'] = null;
				}
			}
		}

		return $data;
	}

	protected function createNewsCategory()
	{
		$category = new Categories([
			'site_id' => $this->id,
			'parent_id' => null,
			'type' => 'articles',
			'title' => s('NEWS'),
			'visibility' => -1,
			'populate' => 'auto',
		]);

		$category->save();

		$extension = Rss::create();
		$extension->set([
			'site_id' => $this->id,
			'category_id' => $category->id,
			'enabled' => 0
		]);

		$extension->save();
		return $category;
	}

	protected function updateFeed($created)
	{
		if (!isset($this->data['feed_url'])) return;

		if (!$category = $this->newsCategory()) {
			$category = $this->createNewsCategory();
		}

		$category->title = $this->data['feed_title'];
		$category->save();

		$extension = $this->newsExtension();
		$extension->url = $this->data['feed_url'];
		$extension->enabled = (int) !empty($this->data['feed_url']);
		$extension->save();
	}

	protected function createRelation($created)
	{
		if (!$created) return;

		Model::load('UsersSites')->add(Auth::user(), $this);
		Auth::user()->site($this->id);
	}

	protected function saveLogoAndAppleTouchIcon()
	{
		$upload = function ($image, $imageModel, $debug = false) {
			if (isset($this->data[$image]) && !$this->data[$image]['error']) {
				if ($item = $this->$image()) {
					Model::load('Images')->delete($item->id);
				}
				Model::load('Images')->upload(new $imageModel($this->id),
					$this->data[$image], ['visible' => 1]);
			}
		};

		$upload('logo', 'SiteLogos');
		$upload('appleTouchIcon', 'SiteAppleTouchIcon');
		$upload('splashScreen', 'SiteSplashScreens', 1);
	}

	protected function savePhoto()
	{
		if (!array_key_exists('photo', $this->data)) return;

		foreach ($this->data['photo'] as $photo) {
			if ($photo['error'] == 0) {
				Model::load('Images')->upload(new SitePhotos($this->id), $photo);
			}
		}
	}

	protected function blacklist($value)
	{
		$blacklist = Config::read('Sites.blacklist');
		return !in_array($value, $blacklist);
	}
}

class SiteImages
{
	public $id;

	public function __construct($id = null)
	{
		$this->id = $id;
	}

	public function id()
	{
		return $this->id;
	}
}

class SiteLogos extends SiteImages
{
	public function resizes()
	{
		return Config::read('SiteLogos.resizes') ?: [];
	}

	public function imageModel()
	{
		return 'SiteLogos';
	}
}

class SiteAppleTouchIcon extends SiteImages
{
	public function resizes()
	{
		return Config::read('SiteAppleTouchIcon.resizes') ?: [];
	}

	public function imageModel()
	{
		return 'SiteAppleTouchIcon';
	}
}

class SiteSplashScreens extends SiteImages
{
	public function resizes()
	{
		return [];
	}

	public function imageModel()
	{
		return 'SiteSplashScreens';
	}
}

class SitePhotos extends SiteImages
{
	public function resizes()
	{
		return Config::read('SitePhotos.resizes') ?: [];
	}

	public function imageModel()
	{
		return 'SitePhotos';
	}

	public function firstById($id)
	{
		return new self($id);
	}
}
