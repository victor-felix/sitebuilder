<?php

namespace meumobi\sitebuilder\repositories;

use DateTime;
use MongoDate;
use MongoId;
use lithium\util\Inflector;
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

	public function findBySiteAndUuid($site_id, $uuid)
	{
		$result = $this->collection()->findOne([
			'site_id' => $site_id,
			'uuid' => $uuid,
		]);

		if ($result) {
			return $this->hydrate($result);
		}
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
		return $this->collection()->remove(['_id' => new MongoId($device->id())]);
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
			'site_id' => $object->siteId(),
			'push_id' => $object->pushId(),
			'model' => $object->model(),
			'platform' => $object->platform(),
			'platform_version' => $object->platformVersion(),
			'app_version' => $object->appVersion(),
			'app_build' => $object->appBuild(),
		];

		return array_merge($data, $this->dehydrateDates($object));
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
				$dates[$field] =  new MongoDate($value->getTimestamp());
			} else {
				$dates[$field] = null;
			}

			return $dates;
		}, []);
	}
}
