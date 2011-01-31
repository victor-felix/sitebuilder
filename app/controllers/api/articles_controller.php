<?php

require 'app/controllers/api/api_controller.php';

class ArticlesController extends ApiController {
    public function api_index($slug = null) {
        $feed = $this->site->feed();
        if($feed) {
            $articles = $feed->topArticles();
        }
        else {
            $articles = array();
        }
        $this->respondToJSON(array(
            'articles' => $articles
        ));
    }
    
    public function api_view($slug, $id = null) {
        $this->respondToJSON(array(
            'articles' => $this->Articles->firstById($id)
        ));
    }
}