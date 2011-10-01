<?php

namespace app\controllers\api;

class ImagesController extends \app\controllers\api\ApiController {
    public function create() {
        $parent = \Model::load('BusinessItems')->firstById($this->param('item_id'));
        $image = \Model::load('Images')->upload($parent, $this->request->data['image'], array(
            'visible' => 0
        ));

        if($image) {
            $this->response->status(201);
            return $this->toJSON(array(
                'images' => $image
            ));
        }
        else {
            $this->response->status(422);
        }
    }

    public function update() {
        
    }
}
