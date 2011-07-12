<?php

namespace app\controllers\api;

class SitesController extends \app\controllers\api\ApiController {
    public function index() {
        return $this->toJSON(array(
            'sites' => \Model::load('Sites')->all()
        ));
    }

    public function view() {
        return $this->toJSON(array(
            'sites' => $this->site
        ));
    }
}
