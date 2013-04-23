<?php

use lithium\data\Connections;

class UpdateThemes
{
	public static function migrate($connection)
	{
		switch (Config::read('App.environment')) {
		case 'development':
		case 'integration':
			$url = 'http://meu-cloud-db.int-meumobilesite.com/configs.json';
			break;
		case 'production':
			$url = 'http://meu-cloud-db.meumobi.com/configs.json';
		}

		$themes = array();

		foreach (json_decode(file_get_contents($url)) as $theme) {
			$themes[$theme->_id] = $theme->name;
		}

		$sites = Model::load('Sites')->all();

		foreach ($sites as $site) {
			$site->theme = $themes[$site->theme];
			$site->skin = Connections::get('default')->connection->skins->findOne(array(
				'theme_id' => $site->theme,
				'main_color' => $site->skin
			))['_id']->{'$id'};
			$site->save();
		}
	}
}
