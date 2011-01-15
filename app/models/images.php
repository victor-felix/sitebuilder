<?php

class Images extends AppModel {
    protected $beforeDelete = array('deleteFile');
    
    public function upload($image, $path) {
        
    }
    
    public function download($image, $path) {
        
    }
    
    public function resize() {
        
    }
    
    protected function deleteFile() {
        
    }
}