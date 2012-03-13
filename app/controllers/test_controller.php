<?php
class TestController extends AppController 
{
    protected $uses = array();
    public function add_invite() {
        $invite = \app\models\Invites::create();
        $data = array(
                'site_id' => 2,
                'host_id' => 12,
                'mail'  => 'tadeu.valentt@gmail.com',
                );
        $invite->set($data);
        echo (int)$invite->save();
        exit;
    }
}