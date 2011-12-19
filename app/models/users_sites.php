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
		
		foreach ($this->allByUserId($user->id) as $item)
			$all[] = $item->site_id;
		
		return $all;
	}
	
	public function getFirstSite($user) {
		if($item = $this->firstByUserId($user->id))
			return $item->site_id;
		else
			return 0;
	}
	
	public function check($userId, $siteId) {
		return $this->exists(array(
				'user_id'	 	=> $userId,
				'site_id'		=> $siteId,
		));
	}
	 
	public function add($user, $site) {
		try{
			
			if($user->segment != $site->segment)
				return false;
			
			if($this->check($user->id, $site->id) )
				return false;
			
			$this->user_id 	= $user->id;
			$this->site_id 		= $site->id;
			$this->segment	= $user->segment;
			
			return  $this->save();
		}catch (\Exception $e){
			return false;
		}
	}
	
	public function onDeleteUser($user) {
		$this->deleteAll( array('user_id' => $user->id) );
	}
	
	public function onDeleteSite($site) {
		$this->deleteAll( array('site_id' => $site->id) );
	}
	
}