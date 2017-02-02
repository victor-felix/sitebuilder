<?php

namespace meumobi\sitebuilder\repositories;

use DateTime;
use MongoDate;
use MongoId;
use Security;
use Model;
use lithium\util\Inflector;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\DevicesRepository;

//TODO Verify the type for site_id. It must be a integer
class VisitorsRepository extends Repository
{
    protected $dateFields = [
        //TODO: Refactor
        //'last_login',
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
        }
     
        throw new RecordNotFoundException("The visitor '{$id}' was not found");
    }

    public function findBySiteId($id)
    {
        return $this->hydrateSet($this->collection()->find(['sites.site_id' => $id]));
    }

    public function findBySiteIdAndGroups($id, $groups)
    {
        return $this->hydrateSet(
            $this->collection()->find(
                [
                    'sites.site_id' => (int) $id,
                    'sites.groups' => ['$in' => $groups]
                ]
            )
        );
    }

    public function findByEmail($email)
    {
        $result = $this->collection()->findOne(
            [
                'email' => strtolower(trim($email))
            ]
        );

        if ($result) {
            return $this->hydrate($result);
        }

        throw new RecordNotFoundException("The visitor with email: '{$email}' was not found");
    }

    public function findByEmailAndSite($email, $siteId)
    {
        $result = $this->collection()->findOne(
            [
                'email' => strtolower(trim($email)),
                'sites.site_id' => (int) $siteId,
            ]
        );

        if ($result) {
            return $this->hydrate($result);
        }
    }

    //TODO: Add $segment parameter (because multisites discussion)
    public function findForAuthentication($siteId, $segment, $email, $password)
    {
        $conditions = [
            'email' => strtolower(trim($email)),
            'hashed_password' => Security::hash($password, 'sha1')
        ];

        if ($siteId) {
            $conditions['sites.site_id'] = $siteId;
        } 
        
        $result = $this->collection()->findOne($conditions);
        
        if ($result) {
            if ($segment) {
                $result['sites'] = $this->filterSitesBySegment($result['sites'], $segment);

            }
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
        return $this->collection()->distinct(
            'groups',
            [
                'site_id' => (int) $siteId
            ]
        );
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

    protected function filterSitesBySegment($sites, $segment)
    {
        $sitesFromSegment = Model::load('Sites')->allBySegment($segment->id);
        $siteIds = array_reduce(
            $sitesFromSegment,
            function ($siteIds, $site) {
                $siteIds[] = ''.$site->id.'';
                return $siteIds;
            },
            []
        );
        $filteredSites = array_reduce(
            $sites,
            function ($filtered, $site) use ($siteIds) {
                if (in_array($site['site_id'], $siteIds)) {
                    $filtered[] = $site;
                }
                return $filtered;
            },
            []
        );
        return $filteredSites;   
    }

    //TODO: Review the hydrate for dates inside array of objects (sites)
    protected function hydrate($data)
    {
        $data = array_merge($data, $this->hydrateDates($data));
        return new Visitor($data);
    }

    //TODO: Review the dehydrate for dates inside array of objects (sites)
    protected function dehydrate($object)
    {
        $data = [
            'email' => $object->email(),
            'first_name' => $object->firstName(),
            'last_name' => $object->lastName(),
            'hashed_password' => $object->hashedPassword(),
            'auth_token' => $object->authToken(),
            'should_renew_password' => $object->shouldRenewPassword(),
            'sites' => $object->sites(),
            'language' => $object->language(),
            'active' => $object->active(),
            //'site_id' => $object->siteId(), //deprecated
            //'groups' => $object->groups() //deprecated
        ];

        return array_merge($data, $this->dehydrateDates($object));
    }
}
