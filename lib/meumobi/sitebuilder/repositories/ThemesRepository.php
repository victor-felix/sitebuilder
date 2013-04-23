<?php

namespace meumobi\sitebuilder\repositories;

use meumobi\sitebuilder\entities\Theme;
use Config;

class ThemesRepository
{
	public function all()
	{
		return array_map(function($theme) {
			return new Theme($theme);
		}, json_decode(file_get_contents(Config::read('Themes.url'))));
	}

	public function bySegment($segment)
	{
		return $this->all();
	}

	public function find($id)
	{
		throw new RecordNotFoundException("The theme '{$id}' was not found");
	}
}
