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

    public function itemExists($params) {
        $conditions = array();
        $joins = array();
        $keys = array_keys($params);

        for($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            $alias = 'v' . $i;
            $joins []= array(
                'table' => array($alias => $this->table()),
                'on' => 'v0.item_id = ' . $alias . '.item_id'
            );
            $conditions[$alias . '.field'] = $key;
            $conditions[$alias . '.value'] = $params[$key];
        }

        $join = array_shift($joins);
        $table = $join['table'];
        return (bool) $this->count(array(
            'table' => $table,
            'joins' => $joins,
            'conditions' => $conditions
        ));
    }
}
