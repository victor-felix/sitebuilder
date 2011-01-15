<?php

class ApiController extends AppController {
    protected function respondTo($format, $data) {
        if($format == 'json') {
            $this->autoRender = false;
            $this->autoLayout = false;
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }
}