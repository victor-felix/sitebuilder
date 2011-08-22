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
            if(isset($themes[$site['theme']])) {
                if(isset($themes[$site['theme']]->colors->{$site['skin']})) {
                    $skin = $site['skin'];
                }
                else {
                    $skin = reset(get_object_vars($themes[$site['theme']]->colors));
                }
                $connection->update(array(
                    'table' => 'sites',
                    'values' => array(
                        'theme' => $themes[$site['theme']]->_id,
                        'skin' => $skin
                    ),
                    'conditions' => array(
                        'id' => $site['id']
                    )
                ));
            }
        }
    }
}
