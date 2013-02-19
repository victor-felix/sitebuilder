<?php

namespace app\controllers\api;

use Model;

class SitesController extends ApiController {
	public function show() {
		$slug = $this->param('slug', $this->param('id'));
		$site = Model::load('Sites')->firstByDomain($slug);
		if($slug) {
			$site = Model::load('Sites')->firstByDomain($slug);
		}
		else {
			$site = $this->site;
		}

		return $this->toJSON($site);
	}
}
