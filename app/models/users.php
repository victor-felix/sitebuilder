<?php
use \lithium\storage\Session;

class Users extends AppModel {
	const CURRENT_SITE = 'User.site';
	
    protected $getters = array('firstname', 'lastname');
    protected $beforeSave = array('hashPassword', 'createToken', 'joinName','addSegment');
    protected $beforeDelete = array('removeSites');
    protected $afterSave = array('authenticate','createSite', 'sendConfirmationMail');
    protected $validates = array(
        'firstname' => array(
            'rule' => 'notEmpty',
            'message' => 'You must fill in all fields'
        ),
        'lastname' => array(
            'rule' => 'notEmpty',
            'message' => 'You must fill in all fields'
        ),
        'email' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'You must fill in all fields'
            ),
            array(
                'rule' => 'email',
                'message' => 'Please enter a valid email address.'
            ),
            array(
                'rule' => array('unique', 'email'),
                'message' => 'There is an existing account associated with this email address.'
            )
        ),
        'password' => array(
            array(
                'rule' => array('minLength', 6),
                'message' => 'The password should contain at least 6 characters.',
                'allowEmpty' => true
            ),
            array(
                'rule' => array('minLength', 6),
                'message' => 'The password should contain at least 6 characters.',
                'on' => 'create'
            )
        ),
        'confirm_password' => array(
            'rule' => array('confirmField', 'password'),
            'message' => 'Passwords do not match'
        )
    );

    public function firstname() {
        if(array_key_exists('name', $this->data)) {
            preg_match('/([^,]+),([^,]+)/', $this->data['name'], $name);
            return $name[1];
        }
    }

    public function lastname() {
        if(array_key_exists('name', $this->data)) {
            preg_match('/([^,]+),([^,]+)/', $this->data['name'], $name);
            return $name[2];
        }
    }

    public function fullname() {
        return preg_replace('/,/', ' ', $this->name);
    }

    public function site($siteId = false) {
    	$model = Model::load('UsersSites');
    	if($siteId && $model->check($this->id, $siteId)) {
    		return Session::write(static::CURRENT_SITE, $siteId);
    	}
    	
    	$currentSiteId = Session::read(static::CURRENT_SITE);
    	
    	if($currentSiteId && $model->check($this->id, $currentSiteId))
    		$siteId = $currentSiteId;
    	else{
    		$siteId = $model->getFirstSite($this);
    		Session::write(static::CURRENT_SITE, $siteId);
    	}
        return Model::load('Sites')->firstById($siteId);
    }
    
    public function sites($removeCurrent = false){
    	$sitesIds = Model::load('UsersSites')->getAllSites($this);
    	$sites = Model::load('Sites')->allById($sitesIds);
    	
    	if($removeCurrent){
    		$current = $this->site();
    		foreach ($sites as $key => $site){
    			if($current->id == $site->id)
    				unset($sites[$key]);
    		}
    	}
    	
    	return $sites;
    } 

    public function hasSiteInSegment($segment) {
        return Model::load('UsersSites')->exists(array(
            'user_id' => $this->id,
            'segment' => $segment
        ));
    }

    public function registerNewSite() {
        $this->createSite(true);
        $this->authenticate(true);
    }

    public function confirm($token) {
        if($token == $this->token) {
            $this->active = 1;
            $this->save();

            return true;
        }
        else {
            return false;
        }
    }

    public function requestForNewPassword($email) {
        if(!empty($email)) {
            $user = $this->firstByEmail($email);
            if($user) {
                $user->sendForgottenPasswordMail();
            }
            else {
                $this->errors['email'] = 'O e-mail não está cadastrado no MeuMobi';
            }
        }
        else {
            $this->errors['email'] = 'Você precisa informar seu e-mail';
        }

        return empty($this->errors);
    }

    public function resetPassword() {
        if($this->validate()) {
            $this->token = $this->newToken();
            $this->save();

            return true;
        }
        else {
            return false;
        }
    }

    protected function hashPassword($data) {
        if(array_key_exists('password', $data) && array_key_exists('confirm_password', $data)) {
            $password = array_unset($data, 'password');
            if(!empty($password)) {
                $data['password'] = Security::hash($password, 'sha1');
            }
            unset($data['confirm_password']);
        }

        return $data;
    }

    protected function createToken($data) {
        if(is_null($this->id)) {
            $data['token'] = $this->newToken();
        }

        return $data;
    }

    protected function newToken() {
        return Security::hash(time(), 'sha1');
    }
	
    protected function removeSites() {
    	return Model::load('UsersSites')->onDeleteUser($this);
    }
    
    protected function createSite($created) {
        if($created) {
            $model = Model::load('Sites');
            $model->save(array(
                'segment' => $this->segment,
                'slug' => '',
                'title' => '',
            ));
        }
    }

    protected function sendConfirmationMail($created) {
        if($created && !Config::read('Mail.preventSending')) {
            require_once 'lib/mailer/Mailer.php';
            $segment = Model::load('Segments')->firstById(MeuMobi::segment());

            $mailer = new Mailer(array(
                'from' => $segment->email,
                'to' => array(
                    $this->email => $this->fullname()
                ),
                'subject' => s('[MeuMobi] Account Confirmation'),
                'views' => array(
                    'text/html' => 'users/confirm_mail.htm'
                ),
                'layout' => 'mail',
                'data' => array(
                    'user' => $this,
                    'title' => s('[MeuMobi] Account Confirmation')
                )
            ));
            $mailer->send();
        }
    }

    protected function sendForgottenPasswordMail() {
        if(!Config::read('Mail.preventSending')) {
            require_once 'lib/mailer/Mailer.php';
            $segment = Model::load('Segments')->firstById(MeuMobi::segment());

            $mailer = new Mailer(array(
                'from' => $segment->email,
                'to' => array(
                    $this->email => $this->fullname()
                ),
                'subject' => s('[MeuMobi] Reset Password Request'),
                'views' => array(
                    'text/html' => 'users/forgot_password_mail.htm'
                ),
                'layout' => 'mail',
                'data' => array(
                    'user' => $this,
                    'title' => s('[MeuMobi] Reset Password Request')
                )
            ));
            $mailer->send();
        }
    }

    protected function authenticate($created) {
        if($created || Auth::loggedIn()) {
            Auth::login($this);
        }
    }
	
    protected function addSegment($data) {
    	$data['segment'] = MeuMobi::segment();
    	return $data;
    }
    
    protected function joinName($data) {
        if(array_key_exists('firstname', $data) && array_key_exists('lastname', $data)) {
            $data['name'] = $data['firstname'] . ',' . $data['lastname'];
        }

        return $data;
    }
}
