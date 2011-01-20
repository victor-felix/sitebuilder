<?php

class UsersController extends AppController {
    public function register() {
        $user = new Users($this->data);
        if(!empty($this->data)) {
            if($user->validate()) {
                $user->save();
                $this->redirect('/sites/register');
            }
            else {
                die(__('Erro de Validação'));
                // TODO http://ipanemax.goplanapp.com/msb/ticket/view/8
            }
        }
        $this->set(array(
            'user' => $user
        ));
    }
}