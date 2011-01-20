<?php

class ApiController extends AppController {
    protected $autoRender = false;
    protected $domain;
    
    protected function beforeFilter() {
        $params = $this->param('params');
        $this->site = Model::load('Sites')->firstByDomain($params[0]);
    }
    
    protected function respondToJSON($record) {
        // header('Content-type: application/json');
        $object = $this->objectTemplate($record);
        echo json_encode($this->toJSON($object));
    }
    
    protected function toJSON($record) {
        if(is_array($record)) {
            foreach($record as $k => $v) {
                $record[$k] = $this->toJSON($v);
            }
        }
        else if($record instanceof Model) {
            $record = $record->toJSON();
        }
        
        return $record;
    }
    
    protected function objectTemplate($content) {
        $controller = $this->param('controller');
        $action = substr($this->param('action'), 4); // remove "api_" from prefixed action
        $templatePath = String::insert(':controller/:action.:ext.tpl', array(
            'controller' => $controller,
            'action' => $action,
            'ext' => 'bkml',
        ));
        
        $site_info = array(
            'title' => $this->site->title,
            'logo' => null,
            'theme' => $this->site->theme,
            'skin' => $this->site->skin,
        );
        if($logo = $this->site->logo()) {
            $site_info['logo'] = $logo->link();
        }
        
        return array(
            'site' => $site_info,
            'templatePath' => $templatePath,
            'content' => $content
        );
    }
}