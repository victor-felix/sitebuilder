<?php

namespace meumobi\sitebuilder\repositories;

use lithium\data\Connections;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\entities\VisitorDevice;

use Connection;
use FileUpload;
use Filesystem;
use MongoClient;
use MongoId;
use Security;

class VisitorsRepository
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
			throw new RecordNotFoundException("The visitor '{$id}' was not found");
		}
	}

	public function findBySiteId($id)
	{
		return $this->hydrateSet($this->collection()->find(['siteId' => $id]));
	}

	public function findByEmailAndPassword($email, $password)
	{
		$result = $this->collection()->findOne([
			'email' => $email,
			'hashedPassword' => Security::hash($password, 'sha1')
		]);

		if ($result) {
			return $this->hydrate($result);
		}
	}

	public function findByAuthToken($authToken)
	{
		$result = $this->collection()->findOne(compact('authToken'));

		if ($result) {
			return $this->hydrate($result);
		}
	}

	public function findAvailableGroupsBySite($site_id)
	{
		return $this->collection()->distinct('groups', [
			'siteId' => $site_id
		]);
	}

	public function create($visitor)
	{
		$data = $this->dehydrate($visitor);
		$result = $this->collection()->insert($data);
		$visitor->setId($data['_id']);

		return $result;
	}

	public function update($visitor)
	{
		$criteria = ['_id' => new MongoId($visitor->id())];
		$data = $this->dehydrate($visitor);

		if ($this->collection()->update($criteria, $data)) {
			return true;
		}

		return false;
	}

	public function destroy($visitor)
	{
		return $this->collection()->remove(['_id' => new MongoId($visitor->id())]);
	}

	protected function connection()
	{
		if ($this->connection) return $this->connection;

		return $this->connection = Connections::get('default')->connection;
	}

	protected function collection()
	{
		if ($this->collection) return $this->collection;

		return $this->collection = $this->connection()->visitors;
	}

	protected function hydrate($data)
	{
		$data['devices'] = array_map(function($d) {
			return new VisitorDevice([
				'uiid' => $d['uiid'],
				'pushId' => $d['pushId'],
				'model' => $d['model'],
			]);
		}, $data['devices']);
		return new visitor($data);
	}

	protected function dehydrate($object)
	{
		return [
			'email' => $object->email(),
			'siteId' => $object->siteId(),
			'hashedPassword' => $object->hashedPassword(),
			'authToken' => $object->authToken(),
			'lastLogin' => $object->lastLogin(),
			'devices' => array_map(function($d) {
				return [
					'uiid' => $d->uiid(),
					'pushId' => $d->pushId(),
					'model' => $d->model()
				];
			}, $object->devices()),
			'groups' => $object->groups()
		];
	}

	protected function hydrateSet($set)
	{
		return array_map(function($data) {
			return $this->hydrate($data);
		}, iterator_to_array($set, false));
	}
}
