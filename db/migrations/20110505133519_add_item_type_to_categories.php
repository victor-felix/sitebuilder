<?php

class AddItemTypeToCategories {
    public static function migrate($connection) {
        $categories = $connection->read(array(
            'table' => 'categories'
        ));

        while($category = $categories->fetch()) {
            $segments = Config::read('Segments');
            $site = $connection->read(array(
                'table' => 'sites',
                'conditions' => array(
                    'id' => $category['site_id']
                )
            ))->fetch();
            $site_segment = $segments[$site['segment']];
            $types = (array) $site_segment['items'];
            $type = $types[0];

            $connection->update(array(
                'table' => 'categories',
                'values' => compact('type'),
                'conditions' => array(
                    'id' => $category['id']
                )
            ));
        }
    }
}
