<?php

class Users extends AppModel {
    protected $getters = array('firstname', 'lastname');
    protected $beforeSave = array('hashPassword', 'createToken', 'joinName');
    protected $afterSave = array('createSite', 'authenticate', 'sendConfirmationMail');
    protected $validates = array(
        'firstname' => array(
            'rule' => 'notEmpty',
            'message' => 'Você precisa informar seu nome'
        ),
        'lastname' => array(
            'rule' => 'notEmpty',
            'message' => 'Você precisa informar seu sobrenome'
        ),
        'email' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Você precisa informar um e-mail válido'
            ),
            array(
                'rule' => 'email',
                'message' => 'Você precisa informar um e-mail válido'
            ),
            array(
                'rule' => array('unique', 'email'),
                'message' => 'Este e-mail já está cadastrado em nossa base de dados'
            )
        ),
        'password' => array(
            array(
                'rule' => array('minLength', 6),
                'message' => 'A senha deve conter 6 ou mais caracteres',
                'allowEmpty' => true
            ),
            array(
                'rule' => array('minLength', 6),
                'message' => 'A senha deve conter 6 ou mais caracteres',
                'on' => 'create'
            )
        ),
        'confirm_password' => array(
            'rule' => array('confirmField', 'password'),
            'message' => 'As senhas informadas não conferem'
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

    public function site() {
        return Model::load('Sites')->firstByUserIdAndSegment($this->id, MeuMobi::$segment);
    }

    public function hasSiteInSegment($segment) {
        return Model::load('Sites')->exists(array(
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
            $this->save(array(
                'active' => 1
            ));

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

        return false;
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

    protected function createSite($created) {
        if($created) {
            $model = Model::load('Sites');
            $model->save(array(
                'segment' => MeuMobi::$segment,
                'slug' => '',
                'title' => '',
                'user_id' => $this->id
            ));
        }
    }

    protected function sendConfirmationMail($created) {
        if($created && !Config::read('Mail.preventSending')) {
            require_once 'lib/mailer/Mailer.php';

            $mailer = new Mailer(array(
                'from' => array(
                    'no-reply@meumobi.com' => 'MeuMobi'
                ),
                'to' => array(
                    $this->email => $this->fullname()
                ),
                'subject' => __('[MeuMobi] Confirmação de Cadastro'),
                'views' => array(
                    'text/html' => 'users/confirm_mail.htm'
                ),
                'layout' => 'mail',
                'data' => array(
                    'user' => $this,
                    'title' => __('[MeuMobi] Confirmação de Cadastro')
                )
            ));
            $mailer->send();
        }
    }

    protected function sendForgottenPasswordMail() {
        if(!Config::read('Mail.preventSending')) {
            require_once 'lib/mailer/Mailer.php';

            $mailer = new Mailer(array(
                'from' => array(
                    'no-reply@meumobi.com' => 'MeuMobi'
                ),
                'to' => array(
                    $this->email => $this->fullname()
                ),
                'subject' => __('[MeuMobi] Redefinição de Senha'),
                'views' => array(
                    'text/html' => 'users/forgot_password_mail.htm'
                ),
                'layout' => 'mail',
                'data' => array(
                    'user' => $this,
                    'title' => __('[MeuMobi] Redefinição de Senha')
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

    protected function joinName($data) {
        if(array_key_exists('firstname', $data) && array_key_exists('lastname', $data)) {
            $data['name'] = $data['firstname'] . ',' . $data['lastname'];
        }

        return $data;
    }
}