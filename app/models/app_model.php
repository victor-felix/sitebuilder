<?php

class AppModel extends Model {
    protected $displayField = 'title';

    public function toJSON() {
        return $this->data;
    }

    public function resizes() {
        $config = Config::read($this->imageModel() . '.resizes');
        if(is_null($config)) {
            $config = array();
        }

        return $config;
    }

    public function firstById($id) {
        $first = $this->first(array(
            'conditions' => array(
                'id' => $id
            )
        ));

        if(is_null($first)) {
            $class = get_class($this);
            throw new \app\models\RecordNotFoundException("{$class} with id={$id} not found");
        }
        else {
            return $first;
        }
    }

    protected function unique($value, $field) {
        $params = array('conditions' => array(
            $field => $value
        ));
        if(!is_null($this->id)) {
            $params['conditions']['id <>'] = $this->id;
        }

        return !$this->count($params);
    }

    protected function confirmField($value, $field) {
        return $value == $this->data[$field];
    }

    protected function asciiOnly($value) {
        return preg_match('/^[\w._-]+$/', $value);
    }

    public function fileUpload($value, $size = null, $types = null) {
        require_once 'lib/utils/FileUpload.php';

        list($valid, $errors) = FileUpload::validate($value, $size, $types);

        return $valid;
    }

    protected function multipleFileUpload($value, $size = null, $types = null) {
        require_once 'lib/utils/FileUpload.php';

        return array_reduce(array_map(function($file) use($size, $types) {
            return FileUpload::validate($file, $size, $types);
        }, $value), function($prev, $next) {
            $prev[0] = $prev[0] && $next[0];
            $prev[1] += $next[1];
            return $prev;
        }, array(true, array()));
    }

    protected function validImage($value) {
        if(is_array($value) && $value['error'] == '0') {
            $image = new Imagick($value['tmp_name']);
            return in_array($image->getImageMimeType(), array('image/jpeg', 'image/png', 'image/gif'));
        }

        return true;
    }

    protected function deleteImages($id) {
        $model = Model::load('Images');
        $images = $model->allByRecord($this->imageModel(), $id);
        $this->deleteSet($model, $images);

        return $id;
    }

    protected function deleteSet($model, $set) {
        foreach($set as $item) {
            $model->delete($item->id);
        }
    }

    public function imageModel() {
        return get_class($this);
    }

    public function id() {
        return $this->id;
    }
}
