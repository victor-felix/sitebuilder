<?php

namespace app\controllers\api;

class SitesController extends \app\controllers\api\ApiController {
    public function index() {
        return $this->toJSON(array(
            'sites' => \Model::load('Sites')->all()
        ));
    }

    public function view($slug = null) {
        if($slug) {
            $site = \Model::load('Sites')->firstBySlug($slug);
        }
        else {
            $site = $this->site;
        }

        return $this->toJSON(array(
            'sites' => $site
        ));
    }
}
