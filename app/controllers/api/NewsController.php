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
        $etag = $this->etag($news);
        $self = $this;

        $this->whenStale($etag, function() use($news, $self) {
            return $self->toJSON(array(
                'articles' => $news
            ));
        });
    }

    public function category($slug = null) {
        $category = $this->site->newsCategory();
        $etag = $this->etag($category);
        $self = $this;
        
        $this->whenStale($etag, function() use($category, $self) {
            return $this->toJSON(array(
                'categories' => $news_category
            ));
        });
    }
}
