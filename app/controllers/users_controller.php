<?php

class UsersController extends AppController {
    public function register() {
        $this->layout = 'register';
        if(!empty($this->data)) {
            $user = new Users($this->data);
            if($user->validate()) {
                $user->save();
                $this->redirect('/');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
    }
}