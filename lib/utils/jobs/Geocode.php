<?php
require_once 'lib/geocoding/GoogleGeocoding.php';

class Geocode
{
    const GEOCODE_LIMIT = 10;

    public function __construct()
    {
        set_time_limit(0);
    }
    public function run()
    {
        $page = 1;
        $jobsToRemove = array();

        while ($jobs = $this->getJobs($page)) {
            foreach ($jobs as $job) {
                //echo $job->_id,"\n"; continue;
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
                            $jobsToRemove[] = (string)$job->_id;
                        break;
                    case 'OVER_QUERY_LIMIT' :
                        break 3;
                    default :
                            $jobsToRemove[] = (string)$job->_id;
                }
            }//end foreach

            if ($page > 10) {
                break;
            }
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
