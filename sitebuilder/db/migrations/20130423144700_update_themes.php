<?php

use lithium\data\Connections;

class UpdateThemes
{
	public static function migrate($connection)
	{
		switch (Config::read('App.environment')) {
		case 'development':
			$url = 'http://meu-cloud-db.int-meumobilesite.com/configs.json';
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
			if (isset($themes[$site->theme])) {
				$skin = Connections::get('default')->connection->skins->findOne(array(
					'theme_id' => $themes[$site->theme],
					'main_color' => $site->skin
				));

				if ($skin) {
					$site->theme = $themes[$site->theme];
					$site->skin = $skin['_id']->{'$id'};
				} else {
					$site->theme = null;
					$site->skin = null;
				}
			} else {
				$site->theme = null;
				$site->skin = null;
			}
			$site->save();
		}
	}
}
