<?php

class UsersController extends AppController {
    protected $redirectIf = array('register', 'login', 'forgot_password', 'reset_password');

    protected function beforeFilter() {
        if(Auth::loggedIn()) {
            foreach($this->redirectIf as $rule) {
                if($rule == $this->param('action')) {
                    $this->redirect('/categories');
                }
            }
        }

        parent::beforeFilter();
    }

    public function edit() {
        $user = $this->Users->firstById(Auth::user()->id);
        $this->saveUser($user, '/users/edit');
    }

    public function register() {
        $user = new Users();
        $this->saveUser($user, '/sites/register');
    }

    public function confirm($id = null, $token = null) {
        $user = $this->Users->firstById($id);
        if($user->confirm($token)) {
            if(!Auth::loggedIn()) {
                Auth::login($user);
            }
            Session::writeFlash('success', __('Cadastro confirmado com sucesso'));
            $this->redirect('/categories');
        }
    }

    public function login() {
        if(!empty($this->data)) {
            $user = Auth::identify($this->data);
            if($user) {
                Auth::login($user);
                $this->redirect('/categories');
            }
            else {
                Session::writeFlash('error', __('Usuário ou senha incorretos'));
            }
        }
    }

    public function logout() {
        Auth::logout();
        $this->redirect('/');
    }

    public function forgot_password() {
        $user = new Users();
        if(!empty($this->data)) {
            if($user->requestForNewPassword($this->data['email'])) {
                die();
            }
        }
        $this->set(array(
            'user' => $user
        ));
    }

    public function reset_password($user_id = null, $token = null) {
        if($user_id) {
            $user = $this->Users->firstById($user_id);

            if($user->token != $token) {
                $this->redirect('/');
            }
        }
        else {
            $this->redirect('/');
        }

        if(!empty($this->data)) {
            $user->updateAttributes($this->data);
            if($user->resetPassword()) {
                Session::writeFlash('success', __('Senha redefinida com sucesso.'));
                $this->redirect('/login');
            }
        }
        $this->set(array(
            'user' => $user
        ));
    }

    protected function saveUser($user, $redirect) {
        if(!empty($this->data)) {
            $user->updateAttributes($this->data);
            if($user->validate()) {
                $user->save();
                Session::writeFlash('success', __('Configurações salvas com sucesso.'));
                $this->redirect($redirect);
            }
        }
        $this->set(array(
            'user' => $user
        ));
    }
}