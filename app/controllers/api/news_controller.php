<?php

class NewsController extends ApiController {
    protected $uses = array('Articles');

    public function api_index($slug = null) {
        $news = $this->BusinessItems->allByParentIdAndSiteId(0, $this->site->id);
        $this->respondToJSON($news);
    }
}
