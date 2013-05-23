<?php

namespace meumobi\sitebuilder\repositories;

require_once 'app/models/segments.php';

use meumobi\sitebuilder\entities\Theme;
use Config;
use Segments;

class ThemesRepository
{
	public function all()
	{
		return array_map(function($theme) {
			$theme->defaults['colors'] = $theme->colors;
			$theme->defaults['main_color'] = $theme->main_color;
			$theme->colors = array_keys((array) $theme->defaults);
			return new Theme($theme);
		}, json_decode(file_get_contents(Config::read('Themes.url'))));
	}

	public function bySegment($segment)
	{
		$theme_ids = Segments::current()->themes();
		$themes = $this->all();

		if (empty($theme_ids)) {
			return $themes;
		} else {
			return array_filter($themes, function($theme) use ($theme_ids) {
				return in_array($theme->id(), $theme_ids);
			});
		}
	}

	public function find($id)
	{
		foreach ($this->all() as $theme) {
			if ($theme->id() == $id) {
				return $theme;
			}
		}
		throw new RecordNotFoundException("The theme '{$id}' was not found");
	}
}