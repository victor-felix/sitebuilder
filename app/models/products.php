<?php

class Products extends BusinessItems {
    protected $typeName = 'Produto';
    protected $fields = array(
        'title' => array(
            'title' => 'Título',
            'type' => 'string'
        ),
        'price' => array(
            'title' => 'Preço',
            'type' => 'string'
        ),
        'description' => array(
            'title' => 'Descrição',
            'type' => 'richtext'
        ),
        'featured' => array(
            'title' => 'Destaque?',
            'type' => 'boolean'
        )
    );
}
