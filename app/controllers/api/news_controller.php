<?php

require 'app/controllers/api/api_controller.php';

class NewsController extends ApiController {
    protected $uses = array('Articles');

    public function api_index($slug = null) {
        $news = $this->Articles->allByParentIdAndSiteId(0, $this->site->id);
        $this->respondToJSON($news);
    }
}
