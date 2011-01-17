<?php

class BusinessItemsValues extends AppModel {
    public function toListByItemId($id) {
        return $this->toList(array(
            'conditions' => array(
                'item_id' => $id
            ),
            'key' => 'field',
            'displayField' => 'id'
        ));
    }
}