<?php

namespace app\controllers\api;

use meumobi\sitebuilder\repositories\SkinsRepository;

class SitesController extends ApiController
{
	public function show()
	{
		return $this->toJSON($this->site());
	}

	public function performance()
	{
		$site = $this->site()->toJSONPerformance();

		$businessKeys = array('email', 'facebook', 'twitter', 'phone',
			'website', 'timetable', 'address', 'latitude', 'longitude');
		$business = array_intersect_key($this->site()->data, array_flip($businessKeys));
		$business['address'] = nl2br($business['address']);
		$business['timetable'] = nl2br($business['timetable']);

		$categoryKeys = array('id', 'title', 'type');
		$extensionKeys = array('url', 'language', 'itemLimit', 'extension');
		$categories = array_map(function($category) use ($categoryKeys, $extensionKeys) {
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
		}, $this->site()->visibleCategories());

		$articleKeys = array('author', 'description', 'title');
		$imageKeys = array('path', 'title', 'description', 'id');
		$news = array_map(function($article) use ($articleKeys, $imageKeys) {
			$json = array_intersect_key($article, array_flip($articleKeys));
			$json['id'] = $article['_id'];
			$json['created_at'] = date('Y-m-d H:i:s', $article['created']);
			$json['updated_at'] = date('Y-m-d H:i:s', $article['modified']);
			$json['published_at'] = date('Y-m-d H:i:s', $article['pubdate']);
			$json['images'] = array_map(function($image) use ($imageKeys) {
				return $image->toJSONPerformance();
			}, \Model::load('Images')->allByRecord('Items', $article['_id']));
			return $json;
		}, $this->site()->news()->to('array'));

		$newsCategory = $this->site->newsCategory();
		$newsCategory = array('title' => $newsCategory->title);

		$skinsRepo = new SkinsRepository();
		$skin = $skinsRepo->find(array_unset($site, 'skin'));
		$site['theme'] = array(
			'theme_id' => $skin->themeId(),
			'colors' => $skin->colors(),
			'assets' => $skin->assets(),
		);

		return compact('site', 'business', 'categories', 'news', 'newsCategory');
	}

	public function theme()
	{
		$skinsRepo = new SkinsRepository();
		$skin = $skinsRepo->find($this->site()->skin);
		$theme = array(
			'theme_id' => $skin->themeId(),
			'colors' => $skin->colors(),
			'assets' => $skin->assets(),
		);

		return $theme;
	}
}
