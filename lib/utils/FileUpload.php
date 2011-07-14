<?php

class FileUpload {
    public $path = '';

    public static function validate($file, $size = null, $types = null) {
        if(empty($file) || $file['error'] == UPLOAD_ERR_NO_FILE) {
            return array(true, array());
        }

        if(!array_key_exists('name', $file)) {
            return array(false, (array) 'InvalidParam');
        }

        list($valid, $error) = self::validateUpload($file);
        if(!$valid) {
            return array(false, (array) $error);
        }

        $errors = array();
        if(!self::validateSize($file, $size)) {
            $errors []= 'FileSizeExceeded';
        }

        if(!self::validateType($file, $types)) {
            $errors []= 'FileTypeNotAllowed';
        }

        return array(empty($errors), $errors);
    }

    public static function validateSize($file, $size) {
        if($size) {
            return $size && filesize($file['tmp_name']) > $size * 1024 * 1024;
        }
        else {
            return true;
        }
    }

    public static function validateType($file, $types) {
        if(!empty($types)) {
            $ext = Filesystem::extension($file['name']);
            return in_array($ext, $types);
        }
        else {
            return true;
        }
    }

    protected static function validateUpload($file) {
        if($file['error'] == UPLOAD_ERR_OK || $file['error'] == UPLOAD_ERR_NO_FILE) {
            return array(true, null);
        }

        $errors = array(
            UPLOAD_ERR_INI_SIZE => 'IniFileSizeExceeded',
            UPLOAD_ERR_FORM_SIZE => 'FormFileSizeExceeded',
            UPLOAD_ERR_PARTIAL => 'PartiallyUploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'MissingTempDir',
            UPLOAD_ERR_CANT_WRITE => 'CantWriteFile'
        );

        if(array_key_exists($file['error'], $errors)) {
            return array(false, $errors[$file['error']]);
        }
        else {
            return array(false, 'UnknownFileError');
        }
    }

    public function upload($file, $name, $path = null) {
        if(is_null($path)) {
            $path = $this->path;
        }

        $path = 'public/' . $path;
        Filesystem::createDir($path, 0755);

        if(Filesystem::hasPermission($path, 'w')) {
            $name = self::getName($file, $name);

            $destination = $path . '/' . $name;
            if(!Filesystem::exists($destination)) {
                Filesystem::moveUploadedFile($file['tmp_name'], $destination);
                return $name;
            }
            else {
                throw new Exception('destination file already exists');
            }
        }
        else {
            throw new Exception('upload folder is not writeable');
        }
    }

    public static function getName($file, $name) {
        return String::insert($name, array(
            'extension' => Filesystem::extension($file['name']),
            'name' => Filesystem::filename($file['name']),
            'original_name' => $file['name']
        ));
    }
}
