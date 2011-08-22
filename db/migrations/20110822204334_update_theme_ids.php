<?php

class UpdateThemeIds {
    public static function migrate($connection) {
        $themes = array();
        $remote = json_decode(file_get_contents('http://meu-cloud-db.int-meumobi.com/configs.json'));

        foreach($remote as $theme) {
            $themes[$theme->name] = $theme;
        }

        $sites = $connection->read(array(
            'table' => 'sites'
        ));

        while($site = $sites->fetch()) {
            echo $site['theme'] . ',' . $themes[$site['theme']]->_id;
        }
    }
}
