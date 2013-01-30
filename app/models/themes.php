<?php

class Themes {
	public function all()
	{
		if (Config::read('Themes.url')) {
			if ($tag = MeuMobi::currentSegment()->themeTag()) {
				$url = sprintf('%s?tags[]=%s', Config::read('Themes.url'), $tag);
			} else {
				$url = Config::read('Themes.url');
			}
		} else {
			$url = sprintf('%s/public/%s/themes/themes.json', APP_ROOT, MeuMobi::segment());
		}

		$themes = file_get_contents($url);
		$themes = json_decode($themes);

		return $themes;
	}

	public function firstById($id)
	{
		$themes = $this->all();

		foreach ($themes as $theme) {
			if ($theme->_id == $id) return $theme;
		}
	}

	public static function thumbPath($thumbnail)
	{
		if (Config::read('TemplateEngine.url')) {
			return Config::read('TemplateEngine.url') . $thumbnail;
		} else {
			return sprintf('/themes/%s', $thumbnail);
		}
	}
}
