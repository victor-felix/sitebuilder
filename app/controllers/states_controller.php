<?php

class StatesController extends AppController {
    public function index($country_id) {
        $states = $this->States->toListByCountryId($country_id);
        $this->respondToJSON($states);
    }
}
