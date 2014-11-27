<?php 
class Video {
	public static function isYoutubeUrl($url) {
		return strpos($url, 'youtube.com') !== false;
	}

	public static function getYoutubeThumbnails($url) {
		$re = "/^.*(youtu.be\\/|v\\/|u\\/\\w\\/|embed\\/|watch\\?v=|\\&v=)([^#\\&\\?]*).*/";
		$sizes = [
			['width' => 120, 'height' => 90, 'url' => 'http://img.youtube.com/vi/%s/default.jpg'],
			['width' => 320, 'height' => 180, 'url' => 'http://img.youtube.com/vi/%s/mqdefault.jpg'],
			['width' => 480, 'height' => 360 , 'url' => 'http://img.youtube.com/vi/%s/hqdefault.jpg'],
			['width' => 640, 'height' => 480 , 'url' => 'http://img.youtube.com/vi/%s/sddefault.jpg'],
			['width' => 1920, 'height' => 1080 , 'url' => 'http://img.youtube.com/vi/%s/maxresdefault.jpg'],
		];

		preg_match_all($re, $str, $matches);
		$videoId = $matches[2][0];//get video id

		return array_map(function($size) use ($videoId) {
			$size['url'] = sprintf($size['url'], $videoId);//add the video it to thumb url
			return $size;
		}, $sizes);
	}

	public static function getThumbnails($url) {
		$thumbnails = [];
		if (static::isYoutubeUrl($url)) {//currently only for youtube videos
			$thumbnails = static::getYoutubeThumbnails($url);
		}
		return $thumbnails;
	}
}
