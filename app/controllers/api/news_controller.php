<?php

require 'app/controllers/api/api_controller.php';

class NewsController extends ApiController {
    protected $uses = array('Articles');

    public function api_index($slug = null) {
        $news_category = $this->site->newsCategory();
        $news = $this->Articles->allOrdered(array(
            'conditions' => array(
                'site_id' => $this->site->id,
                'parent_id' => $news_category->id
            ),
            'limit' => $this->param('limit', 10)
        ));
        $this->respondToJSON(array(
            'articles' => $news
        ));
    }

    public function api_category($slug = null) {
        $news_category = $this->site->newsCategory();
        $this->respondToJSON(array(
            'categories' => $news_category
        ));
    }
}
