<?php

namespace meumobi\sitebuilder\services;

use Exception;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\services\PdfThumbnailer;

class MediaThumbnailer
{
	public static function perform($filePath)
	{
		if (self::getFileType($filePath) != 'pdf') {
			throw new Exception('Invalid file type');
		}

		return PdfThumbnailer::perform([
			'path' => $filePath,
			'extension' => 'png'
		]);
	}

	public static function supportedTypes() {
		return ['application/pdf'];
	}

	protected static function getFileType($file)
	{
		if (!filter_var($file, FILTER_VALIDATE_URL)) return false;

		$headers = get_headers($file, 1);

		if ($headers['Content-Type'] == 'application/pdf'
			|| ($headers['Content-Type'] == 'application/octet-stream'
					&& strpos($headers['Content-Disposition'], '.pdf'))) {
			return 'pdf';
		}

		return false;
	}
}
