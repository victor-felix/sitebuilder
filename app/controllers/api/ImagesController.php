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
        $params = array_merge($this->request->data, compact('visible'));
        $image = Model::load('Images')->upload($item, $data, $params);

        if($image) {
            $this->response->status(201);
            return $this->toJSON($image);
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
        return $this->toJSON($image);
    }

    public function show() {
        $image = Model::load('Images')->firstBySiteIdAndId($this->site()->id, $this->param('id'));
        $etag = $this->etag($image);
        $self = $this;

        return $this->whenStale($etag, function() use($image, $self) {
            return $this->toJSON($image);
        });
    }

    public function destroy() {
        Model::load('Images')->delete($this->param('id'));
        $this->response->status(200);
    }
}
