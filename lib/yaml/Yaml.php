<?php

require 'lib/yaml/sfYaml.php';

class Yaml {
    protected $content;

    public function __construct($filename) {
        $this->load($filename);
    }

    public function get($key, $context = null) {
        if(is_null($context)) {
            $context = $this->content;
        }

        $keys = explode('.', $key);
        $key = array_shift($keys);

        if(!empty($keys)) {
            $keys = implode('.', $keys);
            return $this->get($keys, $context[$key]);
        }
        else {
            return $context[$key];
        }
    }

    protected function load($filename) {
        if($this->isCacheEnabled() && $this->isCacheUpToDate()) {
            return $this->loadFromCache($filename);
        }
        else {
            return $this->loadFromFile($filename);
        }
    }

    protected function loadFromCache($filename) {
        $cache_path = $this->cachePath($filename);
        $cache_content = unserialize(Filesystem::read($cache_path));

        return $this->content = $cache_content;
    }

    protected function loadFromFile($filename) {
        $yaml_content = sfYaml::load(Filesystem::path($filename));

        if($this->isCacheEnabled()) {
            $this->saveToCache($filename, $yaml_content);
        }

        return $this->content = $yaml_content;
    }

    protected function saveToCache($filename, $content) {
        $cache_path = $this->cachePath($filename);
        $cache_content = serialize($content);
        Filesystem::write($cache_path, $cache_content);
    }

    protected function cachePath($filename) {
        return 'tmp/cache/yaml/' . md5($filename);
    }

    protected function isCacheUpToDate($filename) {
        $yaml_path = Filesystem::path($filename);
        $cache_path = Filesystem::path($this->cachePath($filename));

        if(Filesystem::exists($cache_path)) {
            return filemtime($cache_path) >= filemtime($yaml_path);
        }
        else {
            return false;
        }
    }

    protected function isCacheEnabled() {
        return (bool) Config::read('Yaml.cache');
    }
}
