<?php

class MoveSiteIdToUserId {
    public static function migrate($connection) {
        $users = $connection->read(array(
            'table' => 'users'
        ));

        while($user = $users->fetch()) {
            $connection->update(array(
                'table' => 'sites',
                'values' => array(
                    'user_id' => $user['id']
                ),
                'conditions' => array(
                    'id' => $user['site_id']
                )
            ));
        }
    }
}