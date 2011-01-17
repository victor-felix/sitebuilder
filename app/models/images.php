<?php

class Images extends AppModel {
    protected $beforeDelete = array('deleteFile');
    
    public function upload($model, $image, $path) {
        
    }
    
    public function download($model, $image, $path) {
        // throw new ImageNotFoundException();
    }
    
    public function resize() {
        
    }
    
    public function allByRecord($model, $fk) {
        
    }
    
    protected function deleteFile() {
        
    }
}

class ImageNotFoundException extends Exception {}