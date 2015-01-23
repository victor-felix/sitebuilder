<?php
namespace meumobi\sitebuilder\presenters;
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
		$totalDevices = 0;
		$subscribed = 0;
		$unsubscribed = 0;
		$subscribedPercent = 0;
		$unsubscribedPercent = 0;
		$totalVisitors = count($visitors); 
		$accepted = array_reduce($visitors, $countProperty('lastLogin'));
		$pending = $totalVisitors - $accepted;
		$subscribedAndVersions = array_reduce($visitors, function($data, $visitor) use ($countProperty) {
			//total devices
			$data['totalDevices'] += count($visitor->devices());
			//total devices subscribed
			$subscribedDevices = array_reduce($visitor->devices(), $countProperty('pushId'));
			if ($subscribedDevices) $data['subscribed'] += $subscribedDevices;
			//app versions list
			$deviceVersions = array_map(function($device) {
				return $device->appVersion() | '';//must return string
			}, $visitor->devices());
			$data['appVersions'] = array_merge($data['appVersions'], $deviceVersions);
			return $data;
		}, ['totalDevices' => 0, 'subscribed' => 0, 'appVersions' => []]);
		//assing values from array
		extract($subscribedAndVersions);
		if ($totalDevices) {
			$unsubscribed = $totalDevices - $subscribed;
			$subscribedPercent = number_format(($subscribed / $totalDevices) * 100, 2);
			$unsubscribedPercent = number_format(($unsubscribed / $totalDevices) * 100, 2);
		}
		$appVersions = array_count_values($appVersions);
		return compact(
			'totalVisitors',
			'accepted',
			'pending',
			'totalDevices',
			'subscribed',
			'unsubscribed',
			'subscribedPercent',
			'unsubscribedPercent',
			'appVersions'
		);
	}
}
