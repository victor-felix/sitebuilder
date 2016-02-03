<?php

namespace meumobi\sitebuilder\repositories;

use DateTime;
use MongoDate;
use MongoId;
use Security;
use lithium\util\Inflector;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\DevicesRepository;

class VisitorsRepository extends Repository
{
	protected $dateFields = [
		'last_login',
		'created',
		'modified'
	];

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
		return $this->hydrateSet($this->collection()->find(['site_id' => (int) $id]));
	}

	public function findBySiteIdAndGroups($id, $groups)
	{
		return $this->hydrateSet($this->collection()->find([
			'site_id' => (int) $id,
			'groups' => ['$in' => $groups]
		]));
	}

	public function findByEmail($email)
	{
		$result = $this->collection()->findOne([
			'email' => strtolower(trim($email))
		]);

		if ($result) {
			return $this->hydrate($result);
		} else {
			throw new RecordNotFoundException("The visitor with email: '{$email}' was not found");
		}
	}

	public function findByEmailAndSite($email, $siteId)
	{
		$result = $this->collection()->findOne([
			'email' => strtolower(trim($email)),
			'site_id' => (int) $siteId,
		]);

		if ($result) {
			return $this->hydrate($result);
		}
	}

	public function findForAuthentication($siteId, $email, $password)
	{
		$conditions = [
			'email' => strtolower(trim($email)),
			'hashed_password' => Security::hash($password, 'sha1')
		];

		if ($siteId) $conditions['site_id'] = (int) $siteId;

		$result = $this->collection()->findOne($conditions);

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

	public function findAvailableGroupsBySite($siteId)
	{
		return $this->collection()->distinct('groups', [
			'site_id' => (int) $siteId
		]);
	}

	public function create($visitor)
	{
		$visitor->setCreated(new DateTime('NOW'));
		$visitor->setModified(new DateTime('NOW'));
		$data = $this->dehydrate($visitor);
		$result = $this->collection()->insert($data);
		$visitor->setId($data['_id']);

		return $result;
	}

	public function update($visitor)
	{
		$criteria = ['_id' => new MongoId($visitor->id())];
		$visitor->setModified(new DateTime('NOW'));
		$data = $this->dehydrate($visitor);

		if ($this->collection()->update($criteria, $data)) {
			return true;
		}

		return false;
	}

	public function destroy($visitor)
	{
		$id = $visitor->id();
		$result = $this->collection()->remove(['_id' => new MongoId($id)]);

		$devicesRepo = new DevicesRepository();
		$devicesRepo->destroyByUserId($id);

		return $result;
	}

	protected function hydrate($data)
	{
		$data = array_merge($data, $this->hydrateDates($data));

		return new visitor($data);
	}

	protected function dehydrate($object)
	{
		$data = [
			'email' => $object->email(),
			'first_name' => $object->firstName(),
			'last_name' => $object->lastName(),
			'site_id' => $object->siteId(),
			'hashed_password' => $object->hashedPassword(),
			'auth_token' => $object->authToken(),
			'should_renew_password' => $object->shouldRenewPassword(),
			'groups' => $object->groups()
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
