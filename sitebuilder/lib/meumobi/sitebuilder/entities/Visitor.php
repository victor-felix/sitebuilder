<?php

namespace meumobi\sitebuilder\entities;

use DateTimeZone;
use MongoId;
use Security;
use meumobi\sitebuilder\Site;
use meumobi\sitebuilder\repositories\DevicesRepository;
use meumobi\sitebuilder\repositories\RecordNotFoundException;

class Visitor extends Entity
{
    protected $email;
    protected $firstName;
    protected $lastName;
    protected $hashedPassword;
    protected $authToken;
    protected $shouldRenewPassword = false; // true only if role == "visitors" when created
    protected $created;
    protected $modified;

    //New fields
    protected $sites = [];
    protected $language;
    protected $active;

    //To be deprecated
    
    //deprecated
    //protected $siteId;
    //protected $groups = [];
    //protected $lastLogin;

    //deprecated
    // public function siteId()
    // {
    //     return (int) $this->siteId;
    // }

    public function site($siteId)
    {
        $filtering = array_filter(
            $this->sites,
            function ($site) use ($siteId) {
                return $site['site_id'] == $siteId;
            }
        );

        return !empty($filtering)
            ? $filtering[0]
            : [];
    }

    public function sites()
    {
        return (array) $this->sites;
    }

    public function setSites($sites)
    {
        $this->sites = is_array($sites) ? $sites : [];
    }

    public function addSite(array $site)
    {
        $this->sites[] = $site;
    }

    public function email()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = strtolower(trim($email));
    }

    public function firstName()
    {
        return $this->firstName;
    }

    public function lastName()
    {
        return $this->lastName;
    }

    public function fullName()
    {
        return join(' ', [ $this->firstName, $this->lastName ]);
    }

    public function setPassword($password, $shouldRenewPassword = false)
    {
        if (!empty($password)) {
            $this->shouldRenewPassword = $shouldRenewPassword;
            $this->generateAuthToken();
            return $this->hashedPassword = $this->hashPassword($password);
        }
    }

    public function passwordMatch($password)
    {
        return $this->hashPassword($password) == $this->hashedPassword;
    }

    protected function hashPassword($password)
    {
        return Security::hash($password, 'sha1');
    }

    public function hashedPassword()
    {
        return $this->hashedPassword;
    }

    public function authToken()
    {
        return $this->authToken ?: $this->generateAuthToken();
    }

    protected function generateAuthToken()
    {
        return $this->authToken = Security::hash(time() . $this->email(), 'sha1');
    }

    // //deprecated
    public function lastLogin($siteId)
    {
        return $this->lastLoginSite($siteId);
    }

    public function roleSite($siteId)
    {
        $site = $this->site($siteId);
        if ($site) {
            return $site['role'];
        }
        return null;
    }
    public function groupsSite($siteId)
    {
        $site = $this->site($siteId);
        if ($site) {
            return array_unique($site['groups']);
        }
        return null;
    }

    public function lastLoginSite($siteId)
    {
        $site = $this->site($siteId);
        if ($site) {
            return $site['last_login'];
        }
        return null;
    }

    

    //deprecated
    public function setLastLogin($lastLogin)
    {
        //TODO:  To Reimplement
        return ;
        if ($lastLogin) {
            $lastLogin->setTimezone(new DateTimeZone($this->site()->timezone));
        }
        $this->lastLogin = $lastLogin;
    }

    public function created()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function modified()
    {
        return $this->modified;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function shouldRenewPassword()
    {
        return $this->shouldRenewPassword;
    }

    public function isPasswordValid()
    {
        return !$this->shouldRenewPassword;
    }

    //TODO: Review this method to reflect the changes to the new visitor's model
    public function devices()
    {
        $repo = new DevicesRepository();
        return $repo->findByUserId($this->id());
    }

    // //deprecated
    public function groups($siteId)
    {
        return $this->groupsSite($siteId);
    }

    // //deprecated
    // public function setGroups($groups)
    // {
    //     if (is_string($groups)) {
    //         $groups = $groups ? array_map('trim', explode(',', $groups)) : [];
    //     }
    //     $this->groups = $groups;
    // }

    // //deprecated
    // public function addGroup($group)
    // {
    //     if (!in_array($group, $this->groups)) {
    //         $this->groups []= $group;
    //     }
    // }

    // //deprecated
    // public function site()
    // {
    //     return Site::find($this->siteId);
    // }

    public function language()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function active()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }
}
