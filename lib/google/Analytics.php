<?php
//namespace google;
use lithium\storage\Session;

/* Including Oauth 2.0 Api */
require_once 'lib/google/oauth/apiClient.php';

/**
 * Analytics handler
 **/
class Analytics
{
    const OAUTH_TOKEN = 'Google.oauth_token';
    protected $client;
    protected $service;
    protected $site;

    public function __construct()
    {
		/* Only importing here to fix weird bug in Model::load() */
		require_once 'lib/google/oauth/contrib/apiAnalyticsService.php';
        
		$this->site = Auth::user()->site();
        $this->client = new apiClient();
        $this->client->setApplicationName("MeuMobi");
        $this->client->setClientId(Config::read('Google.client_id'));
        $this->client->setClientSecret(Config::read('Google.client_secret'));
        $this->client->setRedirectUri(Mapper::url('/dashboard/google',true));
        $this->client->setDeveloperKey(Config::read('Google.developer_key'));
        $this->client->setAccessType('offline');
        $this->getService();
    }

    public static function logout()
    {
        return Session::delete(self::OAUTH_TOKEN);
    }

    public function authenticate()
    {
        if (isset($_GET['code'])) {
            $auth = $this->client->authenticate();
            Session::write(self::OAUTH_TOKEN, $this->client->getAccessToken());
        } else if ($token = Session::read(self::OAUTH_TOKEN)) {
            $this->client->setAccessToken($token);
        }
        return $this->isAuthenticated();
    }

    public function isAuthenticated()
    {
        return (bool)$this->client->getAccessToken();
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function getService()
    {
        if (!$this->service) {
            $this->service = new apiAnalyticsService($this->client);
        }
        return $this->service;
    }

    public function getProfile()
    {
        return $this->site->google_id;
    }

    public function setProfile($profile)
    {
        $this->site->google_id = (string) $profile;
        $this->site->save();
    }

    public function getRefreshToken()
    {
        return $this->site->google_refresh_token;
    }

    public function setRefreshToken($token)
    {
        $this->site->google_refresh_token = (string) $token;
        $this->site->save();
    }
    
    public function getUiId()
    {
        return $this->site->google_analytics;
    }

    public function setUiId($id)
    {
        $this->site->google_analytics = (string) $id;
        $this->site->save();
    }

    public function getProfiles()
    {
        $profiles = $this->getService()->management_profiles->listManagementProfiles("~all", "~all");
        if ($profiles && count($profiles['items'])) {
            return $profiles['items'];
        }
        return array();
    }
}
