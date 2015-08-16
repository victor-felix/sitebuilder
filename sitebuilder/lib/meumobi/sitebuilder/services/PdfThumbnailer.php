<?php

namespace meumobi\sitebuilder\services;

use Model;
use meumobi\sitebuilder\validators\ParamsValidator;

class PdfThumbnailer
{
	public static function perform($params)
	{
		list($path, $extension) = ParamsValidator::validate($params,
			['path', 'extension']);

		$fileName = md5(uniqid($path, true)) . '.' . $extension;
		$dir = Model::load('Images')->getPath('pdfThumbnail') . '/';
		$to =  $dir . $fileName;

		$imagick = new \Imagick;
		$imagick->readImage($path);
		$imagick->setIteratorIndex(0);
		$imagick->setImageFormat($extension);
		$imagick->writeImages($to, false);
		
		return $to;
	}
}
