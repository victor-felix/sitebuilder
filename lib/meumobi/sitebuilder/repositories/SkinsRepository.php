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
		return $this->hydrateSet($this->collection()->find());
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
		return $this->hydrateSet($this->collection()->find(['theme_id' => $id]));
	}

	public function create($skin)
	{
		$data = $this->dehydrate($skin);
		$result = $this->collection()->insert($data);
		$skin->setId($data['_id']);
		$this->updateSiteEtags($skin->id());
		return $result;
	}

	public function update($skin)
	{
		$data = $this->dehydrate($skin);
		$criteria = ['_id' => new MongoId($skin->id())];
		$this->updateSiteEtags($skin->id());
		return $this->collection()->update($criteria, $data);
	}

	public function destroy($skin)
	{
		return $this->collection()->remove(['_id' => new MongoId($skin->id())]);
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

	protected function dehydrate($object)
	{
		return [
			'theme_id' => $object->themeId(),
			'parent_id' => $object->parentId(),
			'main_color' => $object->mainColor(),
			'colors' => $object->colors(),
			'assets' => $object->assets()
		];
	}

	protected function hydrateSet($set)
	{
		return array_map(function($data) {
			return $this->hydrate($data);
		}, iterator_to_array($set, false));
	}

	protected function updateSiteEtags($skin_id)
	{
		$connection = \Connection::get('default');
		$connection->update([
			'table' => 'sites',
			'conditions' => ['skin' => $skin_id],
			'values' => ['modified' => date('Y-m-d H:i:s')]
		]);
	}
}
