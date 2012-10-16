<?php

class Themes {
	public function all() 
	{
		if (Config::read('Themes.url') && Config::read('multiInstances')) {
			$url = sprintf('%s?tags[]=%s', Config::read('Themes.url'), MeuMobi::segment());
		} else {
			$url = sprintf("%s/public/%s/themes/themes.json", APP_ROOT, MeuMobi::segment());
		}
		
		$themes = file_get_contents($url);
		$themes = json_decode($themes);

		return $themes;
	}

	public function firstById($id) 
	{
		$themes = $this->all();

		foreach($themes as $theme) {
			if($theme->_id == $id) {
				return $theme;
			}
		}
	}
	
	public static function thumbPath($thumbnail) 
	{
		if (Config::read('TemplateEngine.url') && Config::read('multiInstances')) {
			$path = Config::read('TemplateEngine.url') . $thumbnail;
		} else {
			$path = sprintf("/themes/%s", $thumbnail);
		}
		return $path;
	}
}
