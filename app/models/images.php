<?php

class Images extends AppModel {
    protected $beforeDelete = array('deleteFile');
    
    public function upload($model, $image) {
        require_once 'lib/utils/FileUpload.php';
        
        $model_name = get_class($model);
        $id = $model->id;
        
        $uploader = new FileUpload();
        $uploader->path = String::insert('images/:model', array(
            'model' => Inflector::underscore($model_name)
        ));
        
        $this->begin();
        
        try {
            $this->id = null;
            $this->save(array(
                'model' => $model_name,
                'foreign_key' => $model->id
            ));
            
            $filename = $uploader->upload($image, String::insert(':id.:extension', array(
                'id' => $this->id
            )));
            
            $info = $this->getImageInfo($uploader->path, $filename);
            $this->save($info);
            
            $this->commit();
        }
        catch(Exception $e) {
            $this->rollback();
        }
    }
    
    public function download($model, $image, $path) {
        // throw new ImageNotFoundException();
    }
    
    public function resize() {
        
    }
    
    public function allByRecord($model, $fk) {
        return $this->all(array(
            'conditions' => array(
                'model' => $model,
                'foreign_key' => $fk
            )
        ));
    }
    
    public function firstByRecord($model, $fk) {
        return $this->first(array(
            'conditions' => array(
                'model' => $model,
                'foreign_key' => $fk
            )
        ));
    }
    
    public function link() {
        $path = String::insert('/images/:model/:filename', array(
            'model' => Inflector::underscore($this->model),
            'filename' => $this->path
        ));
        return Mapper::url($path, true);
    }
    
    protected function deleteFile() {
        
    }
    
    protected function getImageInfo($path, $filename) {
        $image = new Imagick($path . '/' . $filename);
        $size = $image->getImageLength();
        return array(
            'path' => $filename,
            'type' => $image->getImageMimeType(),
            'filesize' => $size,
            'filesize_octal' => decoct($size)
        );
    }
}

class ImageNotFoundException extends Exception {}