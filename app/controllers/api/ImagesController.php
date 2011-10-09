<?php

namespace app\controllers\api;

use app\models\Items;
use Model;

class ImagesController extends ApiController {
    public function create() {
        $item = Items::find('first', array('conditions' => array(
            'site_id' => $this->site()->id,
            '_id' => $this->request->params['item_id']
        )));

        $data = $this->request->data['image'];
        $visible = isset($this->request->data['visible']) ? $this->request->data['visible'] : 0;
        $params = compact('visible');
        $image = Model::load('Images')->upload($item, $data, $params);

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
        $image = Model::load('Images')->firstById($this->request->params['id']);
        $image->updateAttributes($this->request->data);
        $image->save();
        $this->response->status(200);
        return $this->toJSON(array(
            'images' => $image
        ));
    }
}
