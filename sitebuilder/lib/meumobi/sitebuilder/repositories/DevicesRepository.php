<?php

namespace meumobi\sitebuilder\repositories;

use DateTime;
use MongoId;
use meumobi\sitebuilder\entities\Device;

class DevicesRepository extends Repository
{
	protected $dateFields = ['created', 'modified'];

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
			throw new RecordNotFoundException("The device '{$id}' was not found");
		}
	}

	public function findByUserId($user_id)
	{
		return $this->hydrateSet($this->collection()->find([
			'user_id' => $user_id,
		]));
	}

	public function findForUpdate($site_id, $uuid, $user_id = null)
	{
		$conditions = [
			'site_id' => (int) $site_id,
			'user_id' => $user_id,
			'uuid' => $uuid,
		];

		$result = $this->collection()->findOne($conditions);

		if ($result) {
			return $this->hydrate($result);
		}
	}

	public function findForExport($site_id, $user_ids = null)
	{
		$criteria = [
			'site_id' => (int) $site_id,
			'player_id' => ['$eq' => null],
		];

		if ($user_ids) {
			$criteria['user_id'] = ['$in' => $user_ids];
		}

		return $this->hydrateSet($this->collection()->find($criteria));
	}

	public function findForPushNotif($site_id, $user_ids)
	{
		$criteria = [
			'site_id' => (int) $site_id,
			'push_id' => ['$ne' => null],
		];

		if ($user_ids) {
			$criteria['user_id'] = ['$in' => $user_ids];
		}

		return $this->hydrateSet($this->collection()->find($criteria));
	}

	public function create($device)
	{
		$device->setCreated(new DateTime('NOW'));
		$device->setModified(new DateTime('NOW'));
		$data = $this->dehydrate($device);
		$result = $this->collection()->insert($data);
		$device->setId($data['_id']);

		return $result;
	}

	public function update($device)
	{
		$criteria = ['_id' => new MongoId($device->id())];
		$device->setModified(new DateTime('NOW'));
		$data = $this->dehydrate($device);

		if ($this->collection()->update($criteria, $data)) {
			return true;
		}

		return false;
	}

	public function destroy($device)
	{
		return $this->destroyByCriteria(['_id' => new MongoId($device->id())]);
	}

	public function destroyByUserId($user_id)
	{
		return $this->destroyByCriteria(['user_id' => $user_id]);
	}

	protected function destroyByCriteria($criteria)
	{
		return $this->collection()->remove($criteria);
	}

	protected function hydrate($data)
	{
		$data = array_merge($data, $this->hydrateDates($data));

		return new Device($data);
	}

	protected function dehydrate($object)
	{
		$data = [
			'uuid' => $object->uuid(),
			'user_id' => $object->userId(),
			'site_id' => (int) $object->siteId(),
			'push_id' => $object->pushId(),
			'player_id' => $object->playerId(),
			'model' => $object->model(),
			'manufacturer' => $object->manufacturer(),
			'platform' => $object->platform(),
			'platform_version' => $object->platformVersion(),
			'app_version' => $object->appVersion(),
			'app_build' => $object->appBuild(),
		];

		return array_merge($data, $this->dehydrateDates($object));
	}
}
