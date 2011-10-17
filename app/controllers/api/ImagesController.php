<?php

namespace app\controllers\api;

use app\models\Items;
use Model;

class ImagesController extends ApiController {
    public function index() {
        $images = Model::load('Images')->allByForeignKeyAndVisible($this->request->params['item_id'], 1);
        $etag = $this->etag($images);
        $self = $this;

        return $this->whenStale($etag, function() use($images, $self) {
            return $self->toJSON($images);
        });
    }

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
        $image = Model::load('Images')->firstById($this->request->params['id']);
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
