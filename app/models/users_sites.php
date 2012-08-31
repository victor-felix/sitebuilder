<?php

class UsersSites extends AppModel  {

    protected $table = 'users_sites';
	 
    public function getAllUsers($site) {
        $all = array();

        foreach ($this->allBySiteId($site->id) as $item)
            $all[] = $item->user_id;

        return $all;
    }

    public function getAllSites($user) {
        $all = array();

        foreach ($this->allByUserIdAndSegment($user->id, MeuMobi::segment()) as $item)
            $all[] = $item->site_id;

        return $all;
    }

    public function getFirstSite($user) {
        try{
            if($item = $this->firstByUserIdAndSegment($user->id, MeuMobi::segment()))
                return $item->site_id;
            else
                return false;
        }catch (Exception $e){
            return false;
        }
    }

    public function check($userId, $siteId) {
        return $this->exists(array(
                'user_id'       => $userId,
                'site_id'       => $siteId,
                'segment'   => MeuMobi::segment(),
        ));
    }

    public function add($user, $site, $role = 1) {
        try {

            if(MeuMobi::segment() != $site->segment)
                return false;

            if($this->check($user->id, $site->id) )
                return false;

            $this->user_id = $user->id;
            $this->site_id = $site->id;
            $this->segment = MeuMobi::segment();
            $this->role = $role;

            return  $this->save();
        } catch (Exception $e){
            return false;
        }
    }

    public function remove($user, $site) {
        try {
            if($retalion = $this->firstByUserIdAndSiteIdAndSegment($user->id, $site->id, MeuMobi::segment())) {
                return $retalion->delete($retalion->id);      
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function onDeleteUser($user) {
        if ($user->id) {
            $this->deleteAll( array(
                    'conditions' => array(
                            'user_id' => $user->id)
            ));
        }
    }

    public function onDeleteSite($site) {
        if ($site->id) {
            $this->deleteAll( array(
                    'conditions' => array(
                            'site_id' => $site->id)
                    ));
        }
    }

}
