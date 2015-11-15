<?php

namespace meumobi\sitebuilder\services;

use Imagick;
use MeuMobi;
use Model;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\validators\ParamsValidator;

class PdfThumbnailer
{
	public static function perform($params)
	{
		list($path, $extension) = ParamsValidator::validate($params,
			['path', 'extension']);

		$fileName = md5(uniqid($path, true)) . '.' . $extension;
		$dir = Model::load('Images')->getPath('pdfThumbnail');
		$to =  '/' . $dir . '/' . $fileName;

		Logger::debug('media_thumbnailer', 'downloading file', [ 'url' => $path ]);

		$imagick = new Imagick;
		$imagick->readImage($path);

		Logger::debug('media_thumbnailer', 'file downloaded', [ 'url' => $path ]);

		$imagick->setIteratorIndex(0);
		$imagick->setImageFormat($extension);
		$imagick->writeImages(APP_ROOT . $to, false);

		Logger::debug('media_thumbnailer', 'thumbnail generated', [ 'path' => $to ]);

		return MeuMobi::url($to, true);
	}
}
