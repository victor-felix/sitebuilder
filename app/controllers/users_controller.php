<?php

class UsersController extends AppController {
    protected $redirectIf = array('register', 'login');
    
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
        $user = Auth::user();
        $this->saveUser($user, '/users/edit');
    }
    
    public function register() {
        $user = new Users($this->data);
        $this->saveUser($user, '/users/login');
    }
    
    public function login() {
        if(!empty($this->data)) {
            $user = Auth::identify($this->data);
            if($user && $user->active) {
                if($user->hasSite()) {
                    Auth::login($user);
                    $this->redirect('/categories');
                }
                else {
                    $user->createSite();
                    Auth::login($user);
                    $this->redirect('/sites/register');
                }
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
    
    protected function saveUser($user, $redirect) {
        if(!empty($this->data)) {
            $user->updateAttributes($this->data);
            if($user->validate()) {
                $user->save();
                if($user->hasSite()) {
                    Session::writeFlash('success', __('Configurações salvas com sucesso.'));
                }
                else {
                    Session::writeFlash('success', __('Cadastro realizado com sucesso. Você receberá um e-mail em instantes com instruções para ativar sua conta.'));
                }
                $this->redirect($redirect);
            }
        }
        $this->set(array(
            'user' => $user
        ));
    }
}