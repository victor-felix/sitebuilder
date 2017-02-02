<?php
namespace meumobi\sitebuilder\presenters;
use Mapper;

class AudienceReportPresenter
{
    protected $visitors;
    protected $totalVisitors;
    protected $subscribed;
    protected $accepted;
    protected $devices;
    protected $appVersions;
    protected $site;

    public static function present($visitors, $site)
    {
        $report = new self($visitors, $site);
        return [
            'totalVisitors' => $report->getTotalVisitors(),
            'accepted' => $report->getAccepted(),
            'pending' => $report->getPending(),
            'totalDevices' => $report->getDevices(),
            'subscribed' => $report->getSubscribed(),
            'unsubscribed' => $report->getUnsubscribed(),
            'subscribedPercent' => $report->getPercent($report->getSubscribed(), $report->getDevices()),
            'unsubscribedPercent' => $report->getPercent($report->getUnsubscribed(), $report->getDevices()),
            'appVersions' => $report->getAppVersions(),
        ];
    }

    protected function __construct($visitors, $site)
    {
        $this->visitors = $visitors;
        $this->site = $site;

    }

    protected function getVisitors()
    {
        return $this->visitors;
    }

    protected function getTotalVisitors()
    {
        if (!$this->totalVisitors) $this->totalVisitors = count($this->getVisitors());
        return $this->totalVisitors;
    }

    protected function getAccepted()
    {
        if (!$this->accepted) {
            echo 'foo';
            $this->accepted = (int)array_reduce($this->getVisitors(), $this->countProperty('lastLogin', $this->site->id));
        }
        return $this->accepted;
    }

    protected function getPending()
    {
        return $this->getTotalVisitors() - $this->getAccepted();
    }

    protected function getDevices()
    {
        if (!$this->devices) {
            $this->devices = array_reduce($this->getVisitors(), function($devices, $visitor) {
                $devices += count($visitor->devices());
                return $devices;
            }, 0);
        }
        return $this->devices;
    }

    protected function getAppVersions()
    {
        if (!$this->appVersions) {
            //list of all versions installed
            $allVersions = array_reduce($this->getVisitors(), function($versions, $visitor) {
                foreach ($visitor->devices() as $device) {
                    $versions[] = $device->appVersion() ? $device->appVersion() : s('Undefined');
                }
                return $versions;
            }, []);
            //merge and count versions
            $this->appVersions = array_count_values($allVersions);
        }
        return $this->appVersions;
    }

    protected function getSubscribed()
    {
        if (!$this->subscribed) {
            $this->subscribed = array_reduce($this->getVisitors(), function($total, $visitor) {
                //check if visitor have any device subscribed
                $subscribedDevices = array_reduce($visitor->devices(), $this->countProperty('pushId'));
                if ($subscribedDevices) $total += $subscribedDevices;
                return $total;
            }, 0);
        }
        return $this->subscribed;
    }

    protected function getUnsubscribed()
    {
        return $this->getDevices() - $this->getSubscribed();
    }

    /**
    * Helper methods
     */
    private function countProperty($property, $param = null)
    {
        return function($total, $item) use ($property, $param) {
            if ($item->$property($param)) $total++;
            return $total;
        };
    }

    private function getPercent($amount, $total)
    {
        if (!$total) return 0;
        return number_format(($amount / $total) * 100, 2);
    }
}
