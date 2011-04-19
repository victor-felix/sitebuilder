<?php

class Images extends AppModel {
    protected $beforeDelete = array('deleteFile');

    public function upload($model, $image, $attr = array()) {
        return $this->saveImage('uploadFile', $model, $image, $attr);
    }

    public function download($model, $image, $attr = array()) {
        return $this->saveImage('downloadFile', $model, $image, $attr);
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
        $path = String::insert('/:path/:size:filename', array(
            'model' => Inflector::underscore($this->model),
            'filename' => basename($this->path),
            'path' => dirname($this->path),
            'size' => $size ? $size . '_' : ''
        ));
        return Mapper::url($path, true);
    }

    protected function saveImage($method, $model, $image, $attr) {
        if(!$this->transactionStarted()) {
            $transaction = true;
            $this->begin();
        }
        else {
            $transaction = false;
        }

        try {
            $this->id = null;

            $defaults = array(
                'model' => get_class($model),
                'foreign_key' => $model->id
            );
            $this->save(array_merge($defaults, $attr));

            $path = $this->getPath($model);
            $filename = $this->{$method}($model, $image);

            $info = $this->getImageInfo($path, $filename);
            $filename = $this->renameTempImage($info);

            $info['path'] = $path . '/' . $filename;
            $this->updateAttributes($info);
            $this->save();

            $this->resizeImage($model, $path, $filename);

            if($transaction) {
                $this->commit();
            }

            return $path . '/' . $filename;
        }
        catch(Exception $e) {
            if($transaction) {
                $this->rollback();
            }
            else {
                $this->delete($this->id);
            }
        }
    }

    protected function uploadFile($model, $image) {
        require_once 'lib/utils/FileUpload.php';

        $uploader = new FileUpload();
        $uploader->path = $this->getPath($model);

        return $uploader->upload($image, ':original_name');
    }

    protected function downloadFile($model, $image) {
        require_once 'lib/utils/FileDownload.php';

        $downloader = new FileDownload();
        $downloader->path = $this->getPath($model);

        return $downloader->download($image, ':original_name');
    }

    protected function renameTempImage($info) {
        $types = array(
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif'
        );
        $destination = String::insert(':id.:ext', array(
            'id' => $this->id,
            'ext' => $types[$info['type']]
        ));
        Filesystem::rename('public/' . $info['path'], $destination);

        return $destination;
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
        $filepath = Filesystem::path('public/' . $path . '/' . $filename);
        $image = new Imagick($filepath);
        $size = $image->getImageLength();

        return array(
            'path' => $path . '/' . $filename,
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
