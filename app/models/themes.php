<?php

class Themes {
    public function all() {
        $themes = file_get_contents(Config::read('Themes.url'));
        $themes = json_decode($themes);

        return $themes;
    }
}
