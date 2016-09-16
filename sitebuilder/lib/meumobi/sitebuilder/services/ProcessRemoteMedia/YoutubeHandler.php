<?php

namespace meumobi\sitebuilder\services\ProcessRemoteMedia;

use Madcoda\Youtube;
use meumobi\sitebuilder\Logger;

class YoutubeHandler
{
	const COMPONENT = 'remote_media.youtube_handler';

	public function match($url)
	{
		return $this->getVideoId($url);
	}

	public function perform($url)
	{
		$id = $this->getVideoId($url);
		$youtube = new Youtube(['key' => 'AIzaSyDkAG5Oge5ZOyyVSJKpHpyFY6GsSaE3WIc']);

		$info = $youtube->getVideoInfo($id);
		$snippet = $info->snippet;
		$t = $snippet->thumbnails->high;

		$thumbnail = [
			'url' => $t->url,
			'type' => 'image/jpeg',
			'width' => $t->width,
			'height' => $t->height,
		];

		return [
			[
				'type' => 'application/vnd.youtube.video+html',
				'title' => $snippet->title,
				'duration' => $info->contentDetails->duration,
				'thumbnails' => [$thumbnail],
			],
			null
		];
	}

	protected function getVideoId($url) {
		if (preg_match('/youtube.com\/watch\?v=(\w+)/', $url, $matches)) {
			return $matches[1];
		}
	}
}
