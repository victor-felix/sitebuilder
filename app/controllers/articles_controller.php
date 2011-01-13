<?php

class ArticlesController extends AppController {
    protected function respondTo($format, $data) {
        if($format == 'json') {
            $this->autoRender = false;
            $this->autoLayout = false;
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }
    
    public function index($slug = null) {
        $this->respondTo('json', $this->Articles->allBySiteSlug($slug));
    }
    
    public function view($id = null) {
        $this->respondTo('json', $this->Articles->firstById($id));
    }
}