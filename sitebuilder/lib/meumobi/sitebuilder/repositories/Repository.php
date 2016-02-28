<?php

namespace meumobi\sitebuilder\repositories;

use lithium\data\Connections;

abstract class Repository
{
	protected $connection;
	protected $collection;
	protected $collectionName;

	protected function connection()
	{
		if ($this->connection) return $this->connection;

		return $this->connection = Connections::get('default')->connection;
	}

	protected function collection()
	{
		if ($this->collection) return $this->collection;

		if (!$this->collectionName) {
			$class = (new \ReflectionClass($this))->getShortName();
			$this->collectionName = strtolower(preg_split('/(?!(^|[a-z]|$))/', $class)[0]);
		}

		return $this->collection = $this->connection()->{$this->collectionName};
	}

	protected function hydrateSet($set)
	{
		return array_map(function($data) {
			return $this->hydrate($data);
		}, iterator_to_array($set, false));
	}
}
