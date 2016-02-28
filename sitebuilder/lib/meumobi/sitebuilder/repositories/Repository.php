<?php

namespace meumobi\sitebuilder\repositories;

use MongoDate;
use lithium\data\Connections;
use lithium\util\Inflector;

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

	protected function hydrateDates($data)
	{
		return array_reduce($this->dateFields, function($dates, $field) use ($data) {
			if (isset($data[$field]) && $data[$field] instanceof MongoDate) {
				$dates[$field] = $data[$field]->toDateTime();
			} else {
				$dates[$field] = null;
			}

			return $dates;
		}, []);
	}

	protected function dehydrateDates($object)
	{
		return array_reduce($this->dateFields, function($dates, $field) use ($object) {
			$getter = Inflector::camelize($field);
			$value = $object->$getter();

			if ($value) {
				$dates[$field] = new MongoDate($value->getTimestamp());
			} else {
				$dates[$field] = null;
			}

			return $dates;
		}, []);
	}
}
