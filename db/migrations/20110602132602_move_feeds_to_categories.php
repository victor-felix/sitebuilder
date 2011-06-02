<?php

class MoveFeedsToCategories {
    public static function migrate($connection) {
        $connection->query('
            ALTER TABLE categories
                ADD COLUMN feed_url VARCHAR(255) AFTER title,
                ADD COLUMN updated DATETIME AFTER modified,
                ADD COLUMN visibility TINYINT DEFAULT 1 AFTER feed_url;
        ');

        $feeds = $connection->read(array(
            'table' => 'feeds'
        ));

        while($feed = $feeds->fetch()) {
            if($feed['category_id'] == 0) {
                $parent = $connection->read(array(
                    'table' => 'categories',
                    'conditions' => array(
                        'site_id' => $feed['site_id'],
                        'parent_id' => 0
                    )
                ));

                if($parent = $parent->fetch()) {
                    $connection->create(array(
                        'table' => 'categories',
                        'values' => array(
                            'site_id' => $feed['site_id'],
                            'parent_id' => $parent['id'],
                            'type' => 'articles',
                            'title' => '__news__',
                            'order' => 0,
                            'feed_url' => $feed['link'],
                            'updated' => $feed['updated'],
                            'visibility' => -1
                        )
                    ));
                }
            }
            else {
                $connection->update(array(
                    'table' => 'categories',
                    'values' => array(
                        'feed_url' => $feed['link'],
                        'updated' => $feed['updated']
                    ),
                    'conditions' => array(
                        'id' => $feed['category_id']
                    )
                ));
            }
        }

        $connection->query('DROP TABLE feeds');
        $connection->delete(array(
            'table' => 'business_items_values',
            'conditions' => array(
                'field' => 'feed_id'
            )
        ));
    }
}
