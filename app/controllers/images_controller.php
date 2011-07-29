<?php

require_once 'app/models/sites.php';

class ImagesController extends AppController {
    public function delete($id = null) {
        $this->Images->delete($id);
        if($this->isXhr()) {
            $this->respondToJSON(array('result' => 'ok'));
        }
        else {
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function add(){
        $this->layout = false;

        if(!empty($this->data)) {
            $fk = $this->data['foreign_key'];
            $record = Model::load($this->data['model'])->firstById($fk);
            $image = $this->Images->upload($record, $this->data['photo']);
            $this->set(array(
                'timestamp' => $this->data['timestamp'],
                'image' => $image
            ));
        }
    }
}
