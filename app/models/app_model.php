<?php

class AppModel extends Model {
    protected $displayField = 'title';
    
    public function toJSON() {
        return $this->data;
    }
    
    public function resizes() {
        $config = Config::read(get_class($this) . '.resizes');
        if(is_null($config)) {
            $config = array();
        }
        
        return $config;
    }
    
    protected function unique($value, $field) {
        return !$this->count(array(
            'conditions' => array(
                $field => $value,
                'id <>' => $this->id
            )
        ));
    }

    protected function confirmField($value, $field) {
        return $value == $this->data[$field];
    }
    
    protected function asciiOnly($value) {
        return preg_match('/^[\w._-]+$/', $value);
    }

    protected function fileUpload($value, $size = null, $types = null) {
        require_once 'lib/utils/FileUpload.php';
        
        list($valid, $errors) = FileUpload::validate($value);
        
        return $valid;
    }

    protected function deleteImages($id) {
        $model = Model::load('Images');
        $images = $model->allByRecord(get_class($this), $id);
        $this->deleteSet($model, $images);

        return $id;
    }

    protected function deleteSet($model, $set) {
        foreach($set as $item) {
            $model->delete($item->id);
        }
    }
}