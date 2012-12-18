<?php
use lithium\storage\Session, lithium\util\Validator;

class Users extends AppModel {
	const CURRENT_SITE = 'User.site';

	const ROLE_ADMIN = 1;
	const ROLE_EDITOR = 2;
	const ROLE_USER = 3;

	protected $getters = array('firstname', 'lastname');
	protected $beforeSave = array('hashPassword', 'createToken', 'joinName');
	protected $beforeDelete = array('removeSites');
	protected $afterSave = array('authenticate', 'sendConfirmationMail');
	protected $validates = array (
		'firstname' => array (
			'rule' => 'notEmpty',
			'message' => 'You must fill in all fields'
		),
		'lastname' => array (
			'rule' => 'notEmpty',
			'message' => 'You must fill in all fields'
		),
		'email' => array (
			array (
				'rule' => 'notEmpty',
				'message' => 'You must fill in all fields'
			),
			array (
				'rule' => 'email',
				'message' => 'Please enter a valid email address.'
			),
			array (
				'rule' => array ('unique', 'email' ),
				'message' => 'There is an existing account associated with this email address.'
			)
		),
		'password' => array (
			array (
				'rule' => array ('minLength', 6 ),
				'message' => 'The password should contain at least 6 characters.',
				'allowEmpty' => true
			),
			array (
				'rule' => array ('minLength', 6 ),
				'message' => 'The password should contain at least 6 characters.',
				'on' => 'create'
			)
		),
		'confirm_password' => array (
				'rule' => array ('confirmField', 'password' ),
				'message' => 'Passwords do not match'
		)
	);

	public function firstname()
	{
		if (array_key_exists ( 'name', $this->data )) {
			preg_match ( '/([^,]+),([^,]+)/', $this->data ['name'], $name );
			return $name [1];
		}
	}

	public function lastname()
	{
		if (array_key_exists ( 'name', $this->data )) {
			preg_match ( '/([^,]+),([^,]+)/', $this->data ['name'], $name );
			return $name [2];
		}
	}

	public function fullname()
	{
		return preg_replace('/,/', ' ', $this->name);
	}

	public function addSite($site, $role = 1)
	{
		$model = Model::load('UsersSites');
		return $model->add($this, $site, $role);
	}

	public function site($siteId = false)
	{
		$model = Model::load('UsersSites');
		if ($siteId && $model->check($this->id, $siteId)) {
			return Session::write(static::CURRENT_SITE, $siteId);
		}

		$currentSiteId = Session::read(static::CURRENT_SITE);

		if ($currentSiteId && $model->check($this->id, $currentSiteId)) {
			$siteId = $currentSiteId;
		} else {
			$siteId = $model->getFirstSite($this);
		}

		if ($siteId) {
			Session::write(static::CURRENT_SITE, $siteId);
			$site = Model::load('Sites')->firstById($siteId);
			$userSite = $model->firstByUserIdAndSiteIdAndSegment($this->id, $siteId, MeuMobi::segment());
			$site->role = $userSite->role;
			$site->joined = $userSite->modified;
			return $site;
		}
	}

	public function sites($removeCurrent = false)
	{
		$sitesIds = Model::load ( 'UsersSites' )->getAllSites ( $this );
		$sites = Model::load ( 'Sites' )->allById ( $sitesIds );

		if ($removeCurrent) {
			$current = $this->site ();
			foreach ( $sites as $key => $site ) {
				if ($current->id == $site->id)
					unset ( $sites [$key] );
			}
		}

		return $sites;
	}

	public function hasSiteInSegment($segment) {
		return Model::load ( 'UsersSites' )->exists ( array ('user_id' => $this->id, 'segment' => $segment ) );
	}

	public function hasSiteAsAdmin() {
		return Model::load ( 'UsersSites' )->exists (array(
			'user_id' => $this->id,
			'role' => self::ROLE_ADMIN,
			'segment' => MeuMobi::segment()
		));
	}

	public function registerNewSite() {
		$this->createSite();
		$this->authenticate(true);
	}

	public function confirm($token)
	{
		if ($token == $this->token) {
			$this->active = 1;
			$this->save();
			return true;
		} else {
			return false;
		}
	}

	public function requestForNewPassword($email)
	{
		if (! empty ( $email )) {
			$user = $this->firstByEmail ( $email );
			if ($user) {
				$user->sendForgottenPasswordMail ();
			} else {
				$this->errors ['email'] = 'The e-mail is not registered in MeuMobi';
			}
		} else {
			$this->errors ['email'] = 'You need to provide your e-mail';
		}

		return empty ( $this->errors );
	}

	public function resetPassword()
	{
		if ($this->validate ()) {
			$this->token = $this->newToken ();
			$this->save ();

			return true;
		} else {
			return false;
		}
	}

	public function invite($emails)
	{
		$emails = $this->prepareEmails($emails);
		$site = $this->site();
		foreach ($emails as $email) {
			if ($data = $this->inviteToSite($email, $site)) {
				$data['link'] = Mapper::url("/accept_invite/login/{$data['token']}", true);
				$this->sendInviteEmail($email, "Invited by {$this->fullname()}", $data);
			}
		}
	}

	public function confirmInvite($token)
	{
		$invite = \app\models\Invites::first(array(
			'conditions' => array('token' => $token)
		));

		if (!$invite) return false;

		$site = Model::load('sites')->firstById($invite->site_id);
		$user_role = $this->hasSiteAsAdmin() ? self::ROLE_EDITOR : self::ROLE_USER;

		if ($site && $this->addSite($site, $user_role)) {
			$hostUser = self::firstById($invite->host_id);
			$data = array(
				'site' => $site,
				'invited_user' => $this,
				'host_user' => $hostUser,
			);
			$this->sendInviteEmail($this->email, "Invite confirmed", $data, 'users/invite_confirmed_mail.htm');
			if ($hostUser) {
				$this->sendInviteEmail($hostUser->email, s("{$this->fullname()} confirmed the invitation"), $data, 'users/invite_confirmed_host_mail.htm');
			}
			$this->site($site->id);
			$invite->delete();
			return true;
		}
	}

	public static function validateInvite($token)
	{
		return \app\models\Invites::find('count', array(
			'conditions' => array('token' => $token)
		));
	}

	protected function prepareEmails($emails)
	{
		$chars = array(" ", "\n", "\r", "chr(13)", "\t", "\0", "\x0B");
		$emails = str_replace($chars, '', (string)$emails);

		return array_filter(explode(',', $emails), function($email) {
			return Validator::isEmail($email);
		});
	}

	protected function inviteToSite($email, $site)
	{
		\app\models\Invites::remove(array(
			'site_id' => $site->id,
			'email' => $email
		));

		$data = array(
			'site_id' => $site->id,
			'host_id' => $this->id,
			'email' => $email,
			'token' => Security::hash(time(), 'sha1'),
		);

		$invite = \app\models\Invites::create($data);

		if ($user = self::firstByEmail($email)) {
			$data['user'] = $user;
		}

		return $invite->save() ? $data : false;
	}

	protected function hashPassword($data)
	{
		if (array_key_exists('password', $data) && array_key_exists('confirm_password', $data)) {
			$password = array_unset ($data, 'password');
			if (!empty($password)) {
				$data['password'] = Security::hash($password, 'sha1');
			}
			unset($data ['confirm_password']);
		}

		return $data;
	}

	protected function createToken($data)
	{
		if (is_null ( $this->id )) {
			$data ['token'] = $this->newToken ();
		}

		return $data;
	}

	protected function newToken()
	{
		return Security::hash ( time (), 'sha1' );
	}

	protected function removeSites()
	{
		return Model::load ( 'UsersSites' )->onDeleteUser ( $this );
	}

	protected function createSite()
	{
		$model = Model::load('Sites');
		$model->save(array(
			'segment' => MeuMobi::segment(),
			'slug' => '',
			'title' => ''
		));
	}

	protected function sendConfirmationMail($created)
	{
		if ($created && ! Config::read ( 'Mail.preventSending' )) {
			require_once 'lib/mailer/Mailer.php';
			$segment = MeuMobi::currentSegment();

			$mailer = new Mailer ( array (
						'from' => $segment->email,
						'to' => array ($this->email => $this->fullname () ),
						'subject' => s ( '[MeuMobi] Account Confirmation' ),
						'views' => array ('text/html' => 'users/confirm_mail.htm' ),
						'layout' => 'mail',
						'data' => array (
								'user' => $this,
								'title' => s ( '[MeuMobi] Account Confirmation' )
								)
					));
			$mailer->send ();
		}
	}

	protected function sendForgottenPasswordMail()
	{
		if (!Config::read ( 'Mail.preventSending' )) {
			require_once 'lib/mailer/Mailer.php';
			$segment = MeuMobi::currentSegment();

			$mailer = new Mailer ( array (
				'from' => $segment->email,
				'to' => array ($this->email => $this->fullname () ),
				'subject' => s ( '[MeuMobi] Reset Password Request' ),
				'views' => array ('text/html' => 'users/forgot_password_mail.htm' ),
				'layout' => 'mail',
				'data' => array (
					'user' => $this,
					'title' => s ( '[MeuMobi] Reset Password Request' )
					)
				) );
			$mailer->send ();
		}
	}

	protected function sendInviteEmail($to, $title, $data = array(), $template = 'users/invite_mail.htm')
	{
		if (!Config::read('Mail.preventSending')) {
			require_once 'lib/mailer/Mailer.php';
			$segment = MeuMobi::currentSegment();
			$mailer = new Mailer(array(
						'from' => $segment->email,
						'to' => $to,
						'subject' => $title,
						'views' => array('text/html' => $template ),
						'layout' => 'mail',
						'data' =>  $data,
					));

		 return $mailer->send();
		}
	}

	protected function authenticate($created)
	{
		if ($created || Auth::loggedIn ()) {
			Auth::login ( $this );
		}
	}

	protected function joinName($data)
	{
		if (array_key_exists ( 'firstname', $data ) && array_key_exists ( 'lastname', $data )) {
			$data ['name'] = $data ['firstname'] . ',' . $data ['lastname'];
		}

		return $data;
	}
}
