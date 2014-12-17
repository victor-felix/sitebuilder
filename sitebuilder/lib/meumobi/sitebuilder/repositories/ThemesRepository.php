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
		$data = json_decode(file_get_contents(Config::read('Themes.url')), true);
		if (!is_array($data)) {
			return array();
		}
		return array_map(function($theme) {
			$theme['defaults']['assets'] = isset($theme->assets) ? $theme->assets : array();
			$theme['defaults']['colors'] = isset($theme->colors) ? $theme->colors : array();
			$theme['defaults']['main_color'] = isset($theme->main_color) ? $theme->main_color : '#000';
			$theme['defaults']['html5'] = isset($theme->html5) ? $theme->html5 : false;
			$theme['defaults']['tokens'] = isset($theme->tokens) ? $theme->tokens : array();
			$theme['defaults']['layout_alternatives'] = isset($theme->layout_alternatives) ? $theme->layout_alternatives : array();
			$theme['colors'] = isset($theme['defaults']) ? array_keys((array) $theme['defaults']) : array();

			return new Theme($theme);
		}, $data);
	}

	public function bySegment($segment, $onlyWithSkins = false)
	{
		$theme_ids = Segments::current()->themes();
		$themes = $this->all();

		if (!empty($theme_ids)) {
			$themes = array_filter($themes, function($theme) use ($theme_ids) {
				return in_array($theme->id(), $theme_ids);
			});
		}

		if ($onlyWithSkins) {
			return array_filter($themes, function($theme) {
				return (boolean) $theme->skins();
			});
		}
		return $themes;
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
