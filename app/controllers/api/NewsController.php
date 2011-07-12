<?php

namespace app\controllers\api;

class NewsController extends \app\controllers\api\ApiController {
    public function index() {
        $news_category = $this->site->newsCategory();
        $news = \Model::load('Articles')->allOrdered(array(
            'conditions' => array(
                'site_id' => $this->site->id,
                'parent_id' => $news_category->id
            ),
            'limit' => $this->param('limit', 10)
        ));

        return $this->toJSON(array(
            'articles' => $news
        ));
    }

    public function category($slug = null) {
        $news_category = $this->site->newsCategory();

        return $this->toJSON(array(
            'categories' => $news_category
        ));
    }
}
