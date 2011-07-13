<?php

namespace app\controllers\api;

class SitesController extends \app\controllers\api\ApiController {
    public function index() {
        $sites = \Model::load('Sites')->all();
        $etag = $this->etag($sites);
        $self = $this;

        return $this->whenStale($etag, function() use($sites, $self) {
            return $self->toJSON(array(
                'sites' => $sites
            ));
        });
    }

    public function view($slug = null) {
        if($slug) {
            $site = \Model::load('Sites')->firstBySlug($slug);
        }
        else {
            $site = $this->site;
        }

        $etag = $this->etag($site);
        $self = $this;

        return $this->whenStale($etag, function() use($site, $self) {
            return $self->toJSON(array(
                'sites' => $site
            ));
        });
    }
}
