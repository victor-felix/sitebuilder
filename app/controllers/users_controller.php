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
    
    public function confirm($id = null, $token = null) {
        $user = $this->Users->firstById($id);
        if($user->confirm($token)) {
            $user->createSite();
            Auth::login($user);
            Session::writeFlash('success', __('Cadastro confirmado com sucesso'));
            $this->redirect('/sites/register');
        }
    }
    
    public function login() {
        if(!empty($this->data)) {
            $user = Auth::identify($this->data);
            if($user && $user->active) {
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