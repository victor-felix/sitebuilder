<?php

namespace meumobi\sitebuilder\presenters\api;

use Mapper;
use Model;

class RssItemPresenter
{
	public static function present($item)
	{
		$site = Model::load('Sites')->firstById($item['site_id']);
		$link = "http://{$site->domain()}/items/{$item['_id']}";

		return [
			'title' => $item['title'],
			'description' => $item['description'],
			'published' => date(DATE_RSS, $item['published']),
			'link' => $item['link'] ?: $link,
			'guid' => $item['guid'] ?: $link,
		];
	}

	public static function presentSet($set)
	{
		return array_map(array(__CLASS__, 'present'), $set);
	}
}
