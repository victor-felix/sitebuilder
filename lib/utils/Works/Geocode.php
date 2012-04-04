<?php
namespace utils;
require_once 'lib/utils/Work.php';
require_once 'lib/geocoding/GoogleGeocoding.php';

class Geocode extends Work
{
    const GEOCODE_LIMIT = 100;
    public function init()
    {
    
    }
    
    public function run()
    {
        $page = 1;
        $jobsToRemove = array();
        
        while ($jobs = $this->getJobs($page)) {
            foreach ($jobs as $job) {
                //echo $job->_id,"\n"; continue;
                $classname = '\app\models\items\\' .
                \Inflector::camelize($job->params->type);
                $item = $classname::first(array(
                    'conditions' => array(
                        '_id' => $job->params->item_id,
                    ),
                ));

                if (!$item) {
                    continue;
                }

                $geocode = \GoogleGeocoding::geocode($item->address);
                switch ($geocode->status) {
                    case 'OK' :
                            $location = $geocode->results[0]->geometry->location;
                            $item->geo = array($location->lng, $location->lat);
                            $item->save();
                            echo "item {$item->_id} geocoded\n";
                            $jobsToRemove[] = (string)$job->_id;
                        break;
                    case 'OVER_QUERY_LIMIT' :
                        echo "reached geocode limit\n";
                        break 3;
                    default :
                        echo "cant geocode item {$item->_id}\n";
                            $jobsToRemove[] = (string)$job->_id;
                }
            }//end foreach
            $page++;
        }//end while

        return $this->removeJobs($jobsToRemove);
    }

    protected function removeJobs($jobsIds)
    {
        return \app\models\Jobs::remove(array('_id' => $jobsIds));
    }

    protected function getJobs($page) 
    {
        $jobs = \app\models\Jobs::all(array(
            'conditions' => array('type' => 'geocode'),
            'order' => 'modified',
            'limit' => self::GEOCODE_LIMIT,
            'page' => $page,
        ));

        return count($jobs) ? $jobs : false;
    }
}
