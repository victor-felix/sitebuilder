<?php

namespace app\controllers\api;

use Model;

class SitesController extends ApiController {
    public function show() {
		$this->requireUserAuth();
        $slug = $this->param('slug', $this->param('id'));
        $site = Model::load('Sites')->firstByDomain($slug);
        if($slug) {
            $site = Model::load('Sites')->firstByDomain($slug);
        }
        else {
            $site = $this->site;
        }

        $etag = $this->etag($site);
        $self = $this;

        return $this->whenStale($etag, function() use($site, $self) {
            return $self->toJSON($site);
        });
    }
}
