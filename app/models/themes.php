<?php

class Themes {
    public function all() {
        $url = sprintf('%s?tags[]=%s', Config::read('Themes.url'), MeuMobi::segment());
        $themes = file_get_contents($url);
        $themes = json_decode($themes);

        return $themes;
    }

    public function firstById($id) {
        $themes = $this->all();

        foreach($themes as $theme) {
            if($theme->_id == $id) {
                return $theme;
            }
        }
    }
}
