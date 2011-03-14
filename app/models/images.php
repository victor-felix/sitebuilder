<?php

class Images extends AppModel {
    protected $beforeDelete = array('deleteFile');

    public function upload($model, $image) {
        $this->saveImage('uploadFile', $model, $image);
    }

    public function download($model, $image) {
        $this->saveImage('downloadFile', $model, $image);
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
    
    public function link($size = null) {
        $path = String::insert('/images/:model/:size:filename', array(
            'model' => Inflector::underscore($this->model),
            'filename' => $this->path,
            'size' => $size ? $size . '_' : ''
        ));
        return Mapper::url($path, true);
    }
    
    protected function saveImage($method, $model, $image) {
        if(!$this->transactionStarted()) {
            $transaction = true;
            $this->begin();
        }
        else {
            $transaction = false;
        }
        
        try {
            $this->id = null;
            $this->save(array(
                'model' => get_class($model),
                'foreign_key' => $model->id
            ));
            
            $path = $this->getPath($model);
            $filename = $this->{$method}($model, $image);
            
            $this->resizeImage($model, $path, $filename);
            
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
        $uploader->path = $this->getPath($model);

        return $uploader->upload($image, String::insert(':id.:extension', array(
            'id' => $this->id
        )));
    }

    protected function downloadFile($model, $image) {
        require_once 'lib/utils/FileDownload.php';

        $downloader = new FileDownload();
        $downloader->path = $this->getPath($model);

        $this->save(array(
            'url' => $image
        ));

        return $downloader->download($image, String::insert(':id.:extension', array(
            'id' => $this->id
        )));
    }

    protected function resizeImage($model, $path, $filename) {
        require_once 'lib/phpthumb/ThumbLib.inc.php';
        $fullpath = Filesystem::path('public/' . $path . '/' . $filename);
        $resizes = $model->resizes();
        $modes = array(
            '' => 'resize',
            '#' => 'adaptiveResize',
            '!' => 'cropFromCenter'
        );
        
        foreach($resizes as $resize) {
            $image = PhpThumbFactory::create($fullpath);

            extract($this->parseResizeValue($resize)); // extracts $resize, $w, $h, $mode
            $method = $modes[$mode];
            $image->{$method}($w, $h);

            $image->save(String::insert(':path/:wx:h_:filename', array(
                'path' => Filesystem::path('public/' . $path),
                'filename' => $filename,
                'w' => $w,
                'h' => $h
            )));
        }
    }
    
    protected function deleteFile($id) {
        $self = $this->firstById($id);
        
        Filesystem::delete(String::insert('public/:path/:filename', array(
            'path' => $this->getPath($self->model),
            'filename' => $self->path
        )));
        
        $this->deleteResizedFiles($self->model, $self->path);
        
        return $id;
    }
    
    protected function deleteResizedFiles($model, $filename) {
        $model = Model::load($model);
        $resizes = $model->resizes();
        foreach($resizes as $resize) {
            $values = $this->parseResizeValue($resize);
            Filesystem::delete(String::insert(':path/:wx:h_:filename', array(
                'path' => Filesystem::path('public/' . $this->getPath($model)),
                'filename' => $filename,
                'w' => $values['w'],
                'h' => $values['h']
            )));
        }
    }
    
    protected function parseResizeValue($value) {
        preg_match('/^(\d+)x(\d+)(#|!|>|)$/', $value, $options);
        $keys = array('resize', 'w', 'h', 'mode');
        return array_combine($keys, $options);
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
    
    protected function getPath($model) {
        if(!is_string($model)) {
            $model = get_class($model);
        }
        
        return String::insert('images/:model', array(
            'model' => Inflector::underscore($model)
        ));
    }
}

class ImageNotFoundException extends Exception {}
