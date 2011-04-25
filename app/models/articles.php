<?php

class Articles extends BusinessItems {
    protected $fields = array(
        'feed_id' => array(),
        'guid' => array(),
        'link' => array(),
        'pubdate' => array(),
        'title' => array(
            'title' => 'Título',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Descrição',
            'type' => 'richtext'
        ),
        'author' => array(
            'title' => 'Autor',
            'type' => 'string'
        )
    );

    public function fields() {
        return array('title', 'description', 'author');
    }
}
