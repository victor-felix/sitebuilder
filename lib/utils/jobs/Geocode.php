<?php
require_once 'lib/geocoding/GoogleGeocoding.php';

class Geocode
{
    const GEOCODE_LIMIT = 10;
    
    public function run()
    {
        $page = 1;
        while ($jobs = $this->getJobs($page)) {   
            foreach ($jobs as $job) {
                $classname = '\app\models\items\\' .
                Inflector::camelize($job->params->type);
                $item = $classname::first(array(
                		'conditions' => array(
                				'_id' => $job->params->item_id,
                		),
                ));
                
                if (!$item) {
                    continue;
                }
                
                $geocode = GoogleGeocoding::geocode($item->address);
                switch ($geocode->status) {
                    case 'OK' :
                            $location = $geocode->results[0]->geometry->location;
                            $item->geo = array($location->lng, $location->lat);
                            $item->save();
                        break;
                    case '' :
                        break 3;
                    default :
                        continue;
                }
            }
            
            if($page > 10){
                break;
            }
            $page++;
        }
    }
    
    protected function getJobs($page) {
        $jobs = \app\models\Jobs::all(array(
    		'conditions' => array('type' => 'geocode'),
    		'order' => 'modified',
            'limit' => self::GEOCODE_LIMIT,
            'page' => $page,
        ));
        
        return count($jobs) ? $jobs : false;
    }
    
    public function geocodeItem() 
    {
        
    }
}