<?php
require_once 'lib/google/Analytics.php';

class DashboardController extends AppController 
{
    protected $uses = array();
    protected $analytics;

    protected function beforeFilter()
    {
        $this->analytics =  google\Analytics::load($this->getCurrentSite());
    }

    public function index()
    {
        $this->analytics->authenticate();
        $this->set(array('analytics' => $this->analytics));
    }

    public function google($data = array())
    {   
        $this->analytics->authenticate();
        $this->redirect('/dashboard/index');
    }

    public function profile()
    {
        if ($this->data) {
            list($profileId, $uiId) = explode(',', $this->data['profile']);
            $this->analytics->profile_id = $profileId;
            $this->analytics->profile_uid = $uiId;
            $this->analytics->save();
        }
        $this->redirect('/dashboard/index');
    }
}
