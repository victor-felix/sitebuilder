<?php

class StatesController extends AppController {
    public function index() {
        $states = $this->States->toListByCountryId($this->param('country_id'));
        $this->respondToJSON($states);
    }
}
