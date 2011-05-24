<?php

require 'app/controllers/api/api_controller.php';

class NewsController extends ApiController {
    protected $uses = array('Articles');

    public function api_index($slug = null) {
        $news = $this->Articles->allOrdered(array(
            'conditions' => array(
                'site_id' => $this->site->id,
                'parent_id' => 0
            ),
            'limit' => $this->param('limit', 10)
        ));
        $this->respondToJSON(array(
            'articles' => $news
        ));
    }
}
