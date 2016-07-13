<?php

namespace meumobi\sitebuilder\services;

use Exception;
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

		$image = new Imagick();

		try {
			$image->readImage($url);

			Logger::debug(self::COMPONENT, 'file read', [
				'medium_url' => $url,
			]);
		} catch (Exception $e) {
			Logger::debug(self::COMPONENT, 'file read failed', [
				'medium_url' => $url,
				'http_response_headers' => $http_response_header,
				'message' => $e->getMessage(),
				'exception' => $e,
			]);

			throw $e;
		}

		$image->setIteratorIndex(0);

		if ($image->getImageAlphaChannel()) {
			$image->setImageAlphaChannel(11); // Imagick::ALPHACHANNEL_REMOVE
			$image->setImageBackgroundColor('white');
			$image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
		}

		$image->setImageFormat($extension);
		$image->writeImage(APP_ROOT . $to);

		$geometry = $image->getImageGeometry();

		// tells imagemagick we're done with this, in order to avoid tmp files
		// laying around and filling up our disk.
		$image->clear();

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
