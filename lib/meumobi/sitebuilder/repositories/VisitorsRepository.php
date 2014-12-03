<?php

namespace meumobi\sitebuilder\repositories;

use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\entities\VisitorDevice;

use FileUpload;
use Filesystem;
use MongoClient;
use MongoId;
use Security;

class VisitorsRepository extends Repository
{

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
		return $this->hydrateSet($this->collection()->find(['site_id' => (int)$id]));
	}

	public function findByEmailAndPassword($email, $password)
	{
		$result = $this->collection()->findOne([
			'email' => $email,
			'hashed_password' => Security::hash($password, 'sha1')
		]);

		if ($result) {
			return $this->hydrate($result);
		}
	}

	public function findByAuthToken($authToken)
	{
		$result = $this->collection()->findOne(['auth_token' => $authToken]);
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

	protected function hydrate($data)
	{
		$data['devices'] = array_map(function($d) {
			return new VisitorDevice([
				'uuid' => $d['uuid'],
				'push_id' => $d['push_id'],
				'model' => $d['model'],
			]);
		}, $data['devices']);
		return new visitor($data);
	}

	protected function dehydrate($object)
	{
		return [
			'email' => $object->email(),
			'site_id' => $object->siteId(),
			'hashed_password' => $object->hashedPassword(),
			'auth_token' => $object->authToken(),
			'last_login' => $object->lastLogin(),
			'devices' => array_map(function($d) {
				return [
					'uuid' => $d->uuid(),
					'push_id' => $d->pushId(),
					'model' => $d->model()
				];
			}, $object->devices()),
			'groups' => $object->groups()
		];
	}
}
