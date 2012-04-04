<?php
namespace utils;
require_once 'lib/utils/Work.php';
require_once 'lib/geocoding/GoogleGeocoding.php';

class Geocode extends Work
{
    /*
     * microseconds delay 
     */
    const DELAY_TIME = 250000; 
    
    public function init()
    {
    }
    
    public function run()
    {   
        while ($job = $this->getJob()) {
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
                            $this->log->logInfo("Geocode work: item {$item->_id} geocoded");
                        break;
                    case 'OVER_QUERY_LIMIT' :
                        $this->log->logError('Geocode work: reached geocode limit');
                        break 2;
                    default :
                        $this->log->logError("cant geocode item {$item->_id}");       
                }
                $job->delete();
                usleep(self::DELAY_TIME);
        }//end while

        //return $this->removeJobs($jobsToRemove);
    }

    protected function removeJobs($jobsIds)
    {
        return \app\models\Jobs::remove(array('_id' => $jobsIds));
    }

    protected function getJob() 
    {
        $job = \app\models\Jobs::first(array(
            'conditions' => array('type' => 'geocode'),
            'order' => 'modified',
        ));
        
        return count($job) ? $job : false;
    }
}
