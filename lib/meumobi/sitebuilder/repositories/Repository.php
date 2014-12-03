<?php

namespace meumobi\sitebuilder\repositories;

use lithium\data\Connections;

abstract class Repository
{
	protected $connection;
	protected $collection;

	abstract public function all();

	abstract public function find($id);
	
	abstract public function create($visitor);

	abstract public function update($visitor);

	abstract public function destroy($visitor);

	abstract protected function hydrate($data);

	abstract protected function dehydrate($object);

	protected function connection()
	{
		if ($this->connection) return $this->connection;

		return $this->connection = Connections::get('default')->connection;
	}

	protected function collection()
	{
		if ($this->collection) return $this->collection;

		$class = (new \ReflectionClass($this))->getShortName();
		$collectionName = strtolower(preg_split("/(?!(^|[a-z]|$))/", $class)[0]);//get the collection name from class name

		return $this->collection = $this->connection()->$collectionName;
	}

  protected function hydrateSet($set)
  {
    return array_map(function($data) {
      return $this->hydrate($data);
    }, iterator_to_array($set, false));
  }
}
