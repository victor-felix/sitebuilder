<?php

namespace meumobi\sitebuilder\services;

use Imagick;
use MeuMobi;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class PdfThumbnailer
{
	const COMPONENT = 'media_thumbnailer';

	public static function perform($params)
	{
		list($url, $extension) = ParamsValidator::validate($params,
			['url', 'extension']);

		$fileName = md5(uniqid($url, true)) . '.' . $extension;
		$dir = Model::load('Images')->getPath('pdfThumbnail');
		$to =  '/' . $dir . '/' . $fileName;

		Logger::debug(self::COMPONENT, 'reading file', [
			'medium_url' => $url,
		]);

		$image = new Imagick($url);

		Logger::debug(self::COMPONENT, 'file read', [
			'medium_url' => $url,
		]);

		$image->setIteratorIndex(0);
		$image->setImageFormat($extension);
		$image->writeImages(APP_ROOT . $to, false);

		$geometry = $image->getImageGeometry();

		Logger::debug(self::COMPONENT, 'thumbnail generated', [
			'medium_url' => $url,
			'thumbnail_url' => MeuMobi::url($to, true),
		]);

		return [
			'url' => MeuMobi::url($to, true),
			'type' => 'image/png',
			'width' => $geometry['width'],
			'height' => $geometry['height'],
		];
	}
}
