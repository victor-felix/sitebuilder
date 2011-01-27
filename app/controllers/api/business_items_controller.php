<?php

require 'app/controllers/api/api_controller.php';

class BusinessItemsController extends ApiController {
    public function api_index($domain = null) {
        $type = $this->site->businessItemTypeName();
        $this->respondToJSON(array(
            $type => $this->site->businessItems()
        ));
    }

    public function api_view($domain, $id = null) {
        $bi = $this->BusinessItems->firstById($id);
        $this->respondToJSON(array(
            $bi->type => $bi
        ));
    }
}