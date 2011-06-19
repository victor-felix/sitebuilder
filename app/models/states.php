<?php

class States extends AppModel {
    protected $displayField = 'name';

    public function toListByCountryId($country_id) {
        return $this->toList(array(
            'conditions' => array('country_id' => $country_id)
        ));
    }
}
