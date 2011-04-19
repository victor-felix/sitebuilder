<?php

require_once 'lib/bbcode/Decoda.php';

class BbcodeHelper extends Helper {
    public function parse($text) {
        $parser = new Decoda($text);
        return $parser->parse(true);
    }

    public function strip($text) {
        $text = $this->parse($text);
        return Sanitize::html($text, false);
    }
}
