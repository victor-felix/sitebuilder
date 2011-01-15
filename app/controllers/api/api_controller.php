<?php

class ApiController extends AppController {
    protected $autoRender = false;
    protected $autoLayout = false;
    
    protected function respondToJSON($record) {
        header('Content-type: application/json');
        echo json_encode($this->toJSON($record));
    }
    
    public function toJSON($record) {
        if(is_array($record)) {
            foreach($record as $k => $v) {
                $record[$k] = $this->toJSON($v);
            }
        }
        else {
            $record = $record->toJSON();
        }
        
        return $record;
    }
}