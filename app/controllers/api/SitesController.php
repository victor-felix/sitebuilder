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
		$site = $this->site()->toJSON();

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
		$categories = array_map(function($category) use ($categoryKeys) {
			$data = $category->data;
			$category = array_intersect_key($data, array_flip($categoryKeys));
			$category['created_at'] = $data['created'];
			$category['updated_at'] = $data['modified'];
			return $category;
		}, $this->site()->visibleCategories());

		$news = $this->toJSON($this->site()->news());

		return compact('site', 'business', 'categories', 'news');
	}
}
