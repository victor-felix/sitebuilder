<?php

class Users extends AppModel {
    protected $beforeSave = array('hashPassword');

    protected function hash($data) {
        if(array_key_exists('password', $data)) {
            $password = array_unset($data, "password");
            if(!empty($password)) {
                $data['password'] = Security::hash($password, 'sha1');
            }
        }

        return $data;
    }
}