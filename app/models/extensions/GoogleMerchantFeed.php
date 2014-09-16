<?php

namespace app\models\extensions;

use app\models\Extensions;

class GoogleMerchantFeed extends Extensions
{
	const PRIORITY_HIGH = 2;
	const PRIORITY_MEDIUM = 1;
	const PRIORITY_LOW = 0;

	protected $specification = array(
		'title' => 'Google Merchant Feed',
		'description' => 'Import content automatically from a Google Merchant product feed',
		'type' => 'google-merchant-feed',
		'allowed-items' => array('merchant_products'),
	);

	protected $fields = array(
		'url' => array(
			'title' => 'Feed URL',
			'type' => 'string'
		),
		'product_type' => array(
			'title' => 'Product Type',
			'type' => 'string'
		)
	);

	public static function __init()
	{
		parent::__init();
		$self = static::_object();
		$parent = parent::_object();

		$self->_schema = $parent->_schema + array(
			'url' => array('type' => 'string', 'default' => ''),
			'product_type' => array('type' => 'string', 'default' => ''),
		);
	}
}

GoogleMerchantFeed::applyFilter('save', function($self, $params, $chain) {
	return GoogleMerchantFeed::switchEnabledStatus($self, $params, $chain);
});

GoogleMerchantFeed::applyFilter('save', function($self, $params, $chain) {
	return GoogleMerchantFeed::addTimestampsAndType($self, $params, $chain);
});
