<?php

class Themes {
    public function all() {
        $url = sprintf('%s?segment=%s', Config::read('Themes.url'), MeuMobi::segment());
        $themes = file_get_contents($url);
        $themes = json_decode($themes);

        return $themes;
    }

    public function firstByName($name) {
        return $this->all()->{$name};
    }
}
