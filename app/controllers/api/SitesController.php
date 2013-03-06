<?php

namespace app\controllers\api;

class SitesController extends ApiController
{
	public function show()
	{
		return $this->toJSON($this->site());
	}

	public function performance()
	{
		$site = $this->site()->toJSONPerformance();

		$addressKeys = array('city', 'complement', 'zip', 'zone', 'street',
			'latitude', 'longitude', 'number');
		$address = array_intersect_key($this->site()->data, array_flip($addressKeys));
		$address['country'] = $this->site()->country();
		$address['state'] = $this->site()->state();

		$businessKeys = array('email', 'facebook', 'twitter', 'phone',
			'website', 'timetable');
		$business = array_intersect_key($this->site()->data, array_flip($businessKeys));
		if (array_filter($address)) $business['address'] = $address;
		else $business['address'] = null;

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
				$data = $image->data;
				$json = array_intersect_key($data, array_flip($imageKeys));
				return $json;
			}, \Model::load('Images')->allByRecord('Items', $article['_id']));
			return $json;
		}, $this->site()->news()->to('array'));

		return compact('site', 'business', 'categories', 'news');
	}
}
