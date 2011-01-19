<?php

class Images extends AppModel {
    protected $beforeDelete = array('deleteFile');
    
    public function upload($model, $image) {
        $this->saveImage('uploadFile', $model, $image);
    }
    
    public function download($model, $image) {
        $this->saveImage('downloadFile', $model, $image);
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
    
    protected function saveImage($method, $model, $image) {
        if(!$this->transactionStarted()) {
            $transaction = true;
            $this->begin();
        }
        
        try {
            $this->id = null;
            $this->save(array(
                'model' => get_class($model),
                'foreign_key' => $model->id
            ));
            
            $path = String::insert('images/:model', array(
                'model' => Inflector::underscore(get_class($model))
            ));
            $filename = $this->{$method}($model, $image);
            
            $info = $this->getImageInfo($path, $filename);
            $this->save($info);
            
            if($transaction) {
                $this->commit();
            }
        }
        catch(Exception $e) {
            if($transaction) {
                $this->rollback();
            }
        }
    }
    
    
    protected function uploadFile($model, $image) {
        require_once 'lib/utils/FileUpload.php';

        $uploader = new FileUpload();
        $uploader->path = String::insert('images/:model', array(
            'model' => Inflector::underscore(get_class($model))
        ));

        return $uploader->upload($image, String::insert(':id.:extension', array(
            'id' => $this->id
        )));
    }

    protected function downloadFile($model, $image) {
        require_once 'lib/utils/FileDownload.php';

        $downloader = new FileDownload();
        $downloader->path = String::insert('images/:model', array(
            'model' => Inflector::underscore(get_class($model))
        ));

        return $downloader->download($image, String::insert(':id.:extension', array(
            'id' => $this->id
        )));
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