<?php

namespace meumobi\sitebuilder\presenters\api;

use Mapper;
use Model;
use lithium\util\Inflector;
use meumobi\sitebuilder\presenters\api\SkinPresenter;
use meumobi\sitebuilder\repositories\SkinsRepository;

class SitePresenter
{
	public static function present($site, $skin = null)
	{
		return [
			'site' => self::extractSite($site, $skin),
			'business' => self::extractBusiness($site),
			'categories' => self::extractCategories($site),
			'plugins'=> self::extractPlugins($site),
			'news' => self::extractNews($site),
			'newsCategory' => self::extractNewsCategory($site),
		];
	}

	protected static function extractSite($site, $skin)
	{
		$siteKeys = ['id', 'segment', 'date_format', 'title', 'description',
								'timezone', 'android_app_id', 'ios_app_id',
								'latest_app_version', 'landing_page', 'stock_symbols', 'language'];

		$data = array_intersect_key($site->data, array_flip($siteKeys));

		$data['created_at'] = $site->created;
		$data['updated_at'] = $site->modified;
		$data['description'] = nl2br($data['description']);
		$data['webputty_token'] = $site->css_token;
		$data['analytics_token'] = (strpos($site->google_analytics,',') !== false) ?
																explode(",", $site->google_analytics) : $site->google_analytics;

		$data = array_merge($data,
			self::extractTheme($site, $skin),
			self::extractImages($site)
		);

		return $data;
	}

	protected static function extractTheme($site, $skin)
	{
		$skinId = $skin ? $skin : $site->skin;
		$repository = new SkinsRepository();
		$skin = $repository->find($skinId);

		return ['theme' => SkinPresenter::present($skin)];
	}

	protected static function extractImages($site)
	{
		$types = ['logo', 'apple_touch_icon', 'splash_screen'];

		$images = array_reduce($types, function($images, $type) use ($site) {
			$getter = Inflector::camelize($type, false);
			$image = $site->$getter();
			$images[$type] = $image ? $image->link() : '';

			return $images;
		}, []);

		$photos = $site->photos() ?: [];

		$images['photos'] = array_reduce($photos, function($images, $photo) {
			$images[] = $photo->toJSON();

			return $images;
		}, []);

		return $images;
	}

	protected static function extractBusiness($site)
	{
		$businessKeys = ['email', 'facebook', 'twitter', 'phone', 'website',
										'timetable', 'address', 'latitude', 'longitude'];

		$business = array_intersect_key($site->data, array_flip($businessKeys));

		$business['address'] = nl2br($business['address']);
		$business['timetable'] = nl2br($business['timetable']);

		return $business;
	}

	protected static function extractCategories($site)
	{
		$categoryKeys = ['id', 'title', 'type', 'parent_id'];
		$extensionKeys = ['url', 'language', 'itemLimit', 'extension'];

		return array_map(function($category) use ($categoryKeys, $extensionKeys) {
			$data = $category->data;
			$json = array_intersect_key($data, array_flip($categoryKeys));
			$json['created_at'] = $data['created'];
			$json['updated_at'] = $data['modified'];
			$json['extensions'] = array_map(function($extension) use ($extensionKeys) {
				$json = array_intersect_key($extension, array_flip($extensionKeys));
				$json['id'] = $extension['_id'];
				$json['created_at'] = date('Y-m-d H:i:s', $extension['created']);
				$json['updated_at'] = date('Y-m-d H:i:s', $extension['modified']);

				return $json;
			}, $category->enabledExtensions()->to('array'));

			return $json;
		}, $site->visibleCategories());
	}

	protected static function extractNews($site)
	{
		$articleKeys = ['author', 'description', 'title'];
		$imageKeys = ['path', 'title', 'description', 'id'];

		return array_map(function($article) use ($articleKeys, $imageKeys) {
			$json = array_intersect_key($article, array_flip($articleKeys));
			$json['id'] = $article['_id'];
			$json['created_at'] = date('Y-m-d H:i:s', $article['created']);
			$json['updated_at'] = date('Y-m-d H:i:s', $article['modified']);
			$json['published_at'] = date('Y-m-d H:i:s', $article['published']);
			$json['images'] = array_map(function($image) use ($imageKeys) {
				return $image->toJSONPerformance();
			}, Model::load('Images')->allByRecord('Items', $article['_id']));

			return $json;
		}, $site->news());
	}

	protected static function extractNewsCategory($site)
	{
		$newsCategory = $site->newsCategory();

		return $newsCategory ? ['title' => $newsCategory->title] : '';
	}

	protected static function extractPlugins($site)
	{
		return array_map(function($plugin) {
			return array_diff_key($plugin, array_flip(['modified', 'created', 'site_id']));
		}, $site->plugins()->to('array'));
	}
}
