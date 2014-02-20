<?php
namespace app\models\items;

class ExtendedArticles extends Articles {
	protected $type = 'ExtendedArticles';
	
	protected $fields = array(
			'title' => array(
					'title' => 'Title',
					'type' => 'string'
			),
			'description' => array(
					'title' => 'Description',
					'type' => 'richtext'
			),
			'author' => array(
					'title' => 'Author',
					'type' => 'string'
			),
			'pubdate' => array(
					'title' => 'Publication date',
					'type' => 'datetime'
			),
			'enclosure' => array(
					'title' => 'Enclosure',
					'type' => 'string'
			),
	);
	
	public static function __init()
	{
		parent::__init();
	
		$self = static::_object();
		$parent = parent::_object();
	
		$self->_schema = $parent->_schema + array(
			'guid' => array('type' => 'string', 'default' => ''),
			'link' => array('type' => 'string', 'default' => ''),
			'pubdate' => array('type' => 'datetime', 'default' => 0),
			'description' => array('type' => 'string', 'default' => ''),
			'author' => array('type' => 'string', 'default' => ''),
			'enclosure' => array('type' => 'string', 'default' => ''),
		);
	}
}