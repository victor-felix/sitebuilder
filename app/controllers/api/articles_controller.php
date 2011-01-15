<?php

require 'app/controllers/api/api_controller.php'

class ArticlesController extends AppController {
    public function api_index($slug = null) {
        $this->respondTo('json', $this->Articles->allBySiteSlug($slug));
    }
    
    public function api_view($id = null) {
        $this->respondTo('json', $this->Articles->firstById($id));
    }
}