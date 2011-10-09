<?php

class Segments {
    public function all() {
        return Config::read('Segments');
    }

    public function firstById($id) {
        $segments = $this->all();
        return (object) $segments[$id];
    }

    public static function listItemTypesFor($segment) {
        $segment = Model::load('Segments')->firstById($segment);
        $types = (array) $segment->items;
        $type_list = array();

        foreach($types as $type) {
            $title = Inflector::humanize($type);
            $type_list[$type] = $title;
        }

        return $type_list;
    }
}
