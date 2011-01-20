<?php

class UsersController extends AppController {
    public function edit() {
        $user = $this->Users->first();
        $this->saveUser($user, '/users/edit');
    }
    
    public function register() {
        $user = new Users($this->data);
        $this->saveUser($user, '/sites/register');
    }
    
    public function login() {
        if(!empty($this->data)) {
            $user = Auth::identify($this->data);
            if($user) {
                Auth::login($user);
                $this->redirect('/categories');
            }
        }
    }
    
    public function logout() {
        Auth::logout();
        $this->redirect('/');
    }
    
    protected function saveUser($user, $redirect) {
        if(!empty($this->data)) {
            if($user->validate()) {
                $user->save();
                $this->redirect($redirect);
            }
            else {
                pr($user->errors());
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'user' => $user
        ));
    }
}