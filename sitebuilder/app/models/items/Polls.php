<?php

namespace app\models\items;

use app\models\Items;
use meumobi\sitebuilder\repositories\PollsRepository;

class Polls extends Items
{
	protected $type = 'Poll';

	protected $fields = [
		'title' => [
			'title' => 'Title',
			'type' => 'string',
		],
		'options' => [
			'title' => 'Options',
			'type' => 'multistring',
		],
		'published' => [
			'title' => 'Start Date',
			'type' => 'datetime',
		],
		'end_date' => [
			'title' => 'End Date',
			'type' => 'datetime',
		],
		'multiple_choices' => [
			'title' => 'Multiple Choices?',
			'type' => 'boolean',
		],
		'groups' => [
			'title' => 'Group',
			'type' => 'groups',
		],
	];

	public static function __init()
	{
		parent::__init();

		$self = static::_object();
		$parent = parent::_object();

		$next_week = time() + (7 * 24 * 60 * 60);

		$self->_schema = $parent->_schema + [
			'multiple_choices' => ['type' => 'boolean', 'default' => false],
			'end_date' => ['type' => 'datetime', 'default' => $next_week],
			'options' => ['type' => 'array', 'default' => []],
			'results' => ['type' => 'array', 'default' => []],
		];
	}

	public function userVote($entity, $user)
	{
		$repo = new PollsRepository();
		$votes = $repo->findVotes($entity);

		foreach ($votes as $vote) {
			if ($vote['user_id'] == $user->id()) {
				return $vote;
			}
		}
	}
}

Polls::applyFilter('save', function($self, $params, $chain) {
	return Items::addTimestamps($self, $params, $chain);
});
