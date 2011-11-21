<?php

class RemoveFeedIdFromSites {
    public static function migrate($connection) {
        $sites = $connection->read(array(
            'table' => 'sites',
            'conditions' => array('feed_id IS NOT NULL')
        ));

        while($site = $sites->fetch()) {
            $connection->update(array(
                'table' => 'feeds',
                'values' => array(
                    'site_id' => $site['id']
                ),
                'conditions' => array(
                    'id' => $site['feed_id']
                )
            ));
        }

        $connection->query('ALTER TABLE `sites` DROP COLUMN `feed_id`');
    }
}
