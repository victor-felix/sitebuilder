<?php

namespace app\models\extensions;

use app\models\Extensions;
use meumobi\sitebuilder\services\BulkImportItems;

class Rss extends Extensions
{
    protected $specification = [
        'title' => 'News feed - RSS',
        'description' => 'Import content automatically from a news feed',
        'type' => 'rss',
        'allowed-items' => ['articles'],
    ];

    protected $fields = [
        'url' => [
            'title' => 'Feed URL',
            'type' => 'string',
        ],
        'import_mode' => [
            'title' => 'Method of import',
            'type' => 'select',
            'options' => [
                BulkImportItems::INCLUSIVE_IMPORT => 'Inclusive',
                BulkImportItems::EXCLUSIVE_IMPORT => 'Exclusive',
            ],
        ],
        'use_html_purifier' => [
            'title' => 'Clean html',
            'type' => 'boolean',
        ]
    ];

    public static function __init()
    {
        parent::__init();
        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + [
            'url' => ['type' => 'string', 'default' => ''],
            'use_html_purifier' => ['type' => 'integer', 'default' => 0],
            'import_mode' => ['type' => 'string', 'default' => BulkImportItems::EXCLUSIVE_IMPORT],
            'checksum_news_feed' => ['type' => 'string', 'default' => ''],
        ];
    }
}

Rss::applyFilter('save', function ($self, $params, $chain) {
    return Rss::switchEnabledStatus($self, $params, $chain);
});

Rss::applyFilter('save', function ($self, $params, $chain) {
    return Rss::addType($self, $params, $chain);
});

Rss::applyFilter('save', function ($self, $params, $chain) {
    return Rss::addTimestamps($self, $params, $chain);
});
