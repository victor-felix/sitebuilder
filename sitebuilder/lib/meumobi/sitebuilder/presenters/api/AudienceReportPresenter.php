<?php
namespace meumobi\sitebuilder\presenters\api;
use Mapper;

class AudienceReportPresenter
{
	public static function present($visitors)
	{
		$countProperty = function($property) {
			return function($total, $item) use ($property) {
				if ($item->$property()) $total++;
				return $total;
			};
		};
		$totalVisitors = count($visitors); 
		$accepted = array_reduce($visitors, $countProperty('lastLogin'));
		$invited = $totalVisitors - $accepted;
		$subscribedAndVersions = array_reduce($visitors, function($data, $visitor) use ($countProperty) {
			//total subscribed
			$subscribedDevices = array_reduce($visitor->devices(), $countProperty('pushId'));
			if ($subscribedDevices) $data['subscribed']++;
			//app versions list
			$deviceVersions = array_map(function($device) {
				return $device->appVersion() | '';//must return string
			}, $visitor->devices());
			$data['appVersions'] = array_merge($data['appVersions'], $deviceVersions);
			return $data;
		}, ['subscribed' => 0, 'appVersions' => []]);
		//count app versions
		$unsubscribed = $totalVisitors - $subscribedAndVersions['subscribed'];
		$subscribedPercent = $totalVisitors ? number_format(($subscribedAndVersions['subscribed'] / $totalVisitors), 2) * 100 : 0;
		$unsubscribedPercent = $totalVisitors ? 100 - $subscribedPercent : 0;
		$subscribedAndVersions['appVersions'] = array_count_values($subscribedAndVersions['appVersions']);
		return compact('totalVisitors', 'accepted', 'invited', 'unsubscribed', 'subscribedPercent', 'unsubscribedPercent') + $subscribedAndVersions;
	}
}
