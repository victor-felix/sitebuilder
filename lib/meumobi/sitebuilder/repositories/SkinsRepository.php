<?php

namespace meumobi\sitebuilder\repositories;

use lithium\data\Connections;
use meumobi\sitebuilder\entities\Skin;
use MongoClient;
use MongoId;

class SkinsRepository
{
	protected $connection;
	protected $collection;

	public function all()
	{
		return $this->collection()->find();
	}

	public function find($id)
	{
		$result = $this->collection()->findOne(['_id' => new MongoId($id)]);

		if ($result) {
			return $this->hydrate($result);
		} else {
			throw new RecordNotFoundException("The skin '{$id}' was not found");
		}
	}

	public function findByThemeId($id)
	{
		return array_map(function($theme) {
			return $this->hydrate($theme);
		}, iterator_to_array($this->collection()->find(['theme_id' => $id])));
	}

	protected function connection()
	{
		if ($this->connection) return $this->connection;

		return $this->connection = Connections::get('default')->connection;
	}

	protected function collection()
	{
		if ($this->collection) return $this->collection;

		return $this->collection = $this->connection()->skins;
	}

	protected function hydrate($data)
	{
		return new Skin($data);
	}

	public function firstByEmail($email)
	{
	}
}
