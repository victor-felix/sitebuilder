<?php
namespace google;
use lithium\storage\Session;

/* Including Oauth 2.0 Api */
require_once 'lib/google/oauth/apiClient.php';

/**
 * Analytics handler
 **/
class Analytics extends \lithium\data\Model
{
    const OAUTH_TOKEN = 'Google.oauth_token';
    protected $client;
    protected $service;
    protected $startDate;
    protected $endDate;
    
    //protected $getters = array();
    //protected $setters = array();
    protected $_meta = array(
        //'name' => null,
        'title' => null,
        //'class' => null,
        'source' => 'analytics',
        'connection' => 'default',
        'initialized' => false,
        'key' => '_id',
        'locked' => false
    );

    protected $_schema = array(
        '_id'  => array('type' => 'id'),
        'site_id' => array('type' => 'integer', 'null' => false),
        'profile_id'  => array('type' => 'string', 'default' => ''),
        'profile_uid' => array('type' => 'string', 'default' => ''),
        'refresh_token'  => array('type' => 'string', 'default' => ''),
        //'created'  => array('type' => 'date', 'default' => 0),
        //'modified'  => array('type' => 'date', 'default' => 0),
    );

    public static function load(\Sites $site)
    {
        $self = self::findAllBySiteId($site->id)->rewind();
        if (!count($self)) {
            $self = self::create();
            $self->site_id = $site->id;
            $self->save();
        }
        $self->service();
       
        return $self;
    }

    public function service()
    {
        $this->client = new \apiClient();
        $this->client->setApplicationName("MeuMobi");
        $this->client->setClientId(\Config::read('Google.client_id'));
        $this->client->setClientSecret(\Config::read('Google.client_secret'));
        $this->client->setRedirectUri(\Mapper::url('/dashboard/google',true));
        $this->client->setDeveloperKey(\Config::read('Google.developer_key'));
        $this->client->setAccessType('offline');
        $this->getService();
    }

    public static function logout()
    {
        return Session::delete(self::OAUTH_TOKEN);
    }

    public function authenticate($self)
    {
        if (isset($_GET['code'])) {
            $auth = $this->client->authenticate();
            $data =  json_decode($this->client->getAccessToken());
            $self->refresh_token = $data->refresh_token;
            $self->save();
            Session::write(self::OAUTH_TOKEN, $this->client->getAccessToken());
        } else if ($token = Session::read(self::OAUTH_TOKEN)) {
            $data =  json_decode($token, true);
            
            /*  add refresh token if not exists*/
            if (!array_key_exists('refresh_token', $data)) {
                $data['refresh_token'] = $self->refresh_token;
            }
            
            $this->client->setAccessToken(json_encode($data));
        } else if ($self->refresh_token) {
            $this->client->refreshToken($self->refresh_token);
            Session::write(self::OAUTH_TOKEN, $this->client->getAccessToken());
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
            /* Only importing here to fix namespace bug */
            require_once 'lib/google/oauth/contrib/apiAnalyticsService.php';
            $this->service = new \apiAnalyticsService($this->client);
        }
        return $this->service;
    }

    public function getProfiles()
    {
        $profiles = $this->getService()->management_profiles->listManagementProfiles("~all", "~all");
        if ($profiles && count($profiles['items'])) {
            return $profiles['items'];
        }
        return array();
    }
    
    public function setRange($startDate, $endDate, $format = 'Y-m-d')
    {
        $this->startDate = \DateTime::createFromFormat( $format, $startDate);
        $this->endDate = \DateTime::createFromFormat( $format, $endDate);
    }
    
    public function getTraffic($self)
    {
        $metrics = 'ga:visits, ga:avgTimeOnSite, ga:pageviews, ga:percentNewVisits';
        $dimensions = 'ga:day';
        $params = array(
                'dimensions' => $dimensions,
        );
        return $this->fetchData($self, $metrics, $params);
    }
    
    public function getMobileTraffic($self)
    {
        $metrics = 'ga:visits';
        $dimensions = 'ga:operatingSystem,ga:screenResolution';
        $params = array(
                'dimensions' => $dimensions,
                'sort' => '-ga:visits',
                //'max-results' => 10,
                'segment' => 'gaid::-11'
        );
        
        $result = $this->fetchData($self, $metrics, $params);
        $totalVisits = $result['totalsForAllResults']['ga:visits'];
        $headers = array();
        $trafficBySystem = array();
        $trafficByScreen = array();
        
        foreach ($result['columnHeaders'] as $index => $header) {
            $headers[$header['name']] = $index;
        }
        
        foreach ($result['rows'] as $row) {
            $system = $row[$headers['ga:operatingSystem']];
            $screen = $row[$headers['ga:screenResolution']];
            $visits = $row[$headers['ga:visits']];
            @$trafficBySystem[$system] += $visits;
            @$trafficByScreen[$screen] += $visits;
        }
        
        return array(
                    'system' => $trafficBySystem,
                    'screen' => $trafficByScreen,
                    'total' => $totalVisits,
                );
    }
    
    public function getTopPages($self, $limit = 5)
    {
        $metrics = 'ga:pageviews';
        $dimensions = 'ga:pageTitle';
        $params = array(
            'dimensions' => $dimensions,
            'sort' => '-ga:pageviews', 
            'max-results' => $limit,
        );
        $result = $this->fetchData($self, $metrics, $params);
        return $result['rows'];
    }
    
    public function fetchData($self, $metrics, $params)
    {
        if (!$this->startDate || !$this->endDate) {
            $this->startDate = new \DateTime();
            $this->endDate = new \DateTime();
            $this->startDate->sub(new \DateInterval('P1M'));
        }
        return $data = $this->getService()->data_ga->get(
                'ga:' . $self->profile_id,
                $this->startDate->format('Y-m-d'), 
                $this->endDate->format('Y-m-d'),
                $metrics,
                $params);
    }
    
}
