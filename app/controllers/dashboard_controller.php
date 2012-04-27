<?php
require_once 'lib/google/Analytics.php';

class DashboardController extends AppController 
{
    protected $uses = array();

    public function index()
    {
        $analytics = new Analytics();
        $analytics->authenticate();
        $this->set(compact('analytics'));
    }

    public function google()
    {
        $analytics = new Analytics();
        $analytics->authenticate();
        $this->redirect('/dashboard/index');
    }

    public function profile()
    {
       // if ($this->data) {
        $site = $this->getCurrentSite();
        $site->title ='carai';
        $site->save();
            /*
            list($profileId, $uiId) = explode(',', $this->data['profile']);
            $analytics = new Analytics();
            $analytics->setProfile($profileId);
            //$analytics->setUiId($uiId);*/
        //}
        //$this->redirect('/dashboard/index');
    }
}
