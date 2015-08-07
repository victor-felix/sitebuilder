<?php

namespace meumobi\sitebuilder\workers;
require_once 'lib/phpthumb/ThumbLib.inc.php';

use app\models\Items;
use meumobi\sitebuilder\services\PdfThumbnailer;


class PdfThumbnailerWorker extends Worker
{
	public function perform()
	{
		$this->logger()->info('start create pdf thumbnails', [
			'item id'  => $this->getItem()->_id,
		]);
		try {
			//must use array to prevent a lithiun persistence bug
			$item = $this->getItem()->to('array');
			array_walk($item['medias'],[$this, 'createThumbnail']);
			$this->getItem()->medias = $item['medias'];
			$this->getItem()->save();
			$this->logger()->info('Media thumbnails created', [
				'item id'  => $this->getItem()->_id,
			]);
		} catch(\Exception $e) {
			$this->logger()->info('Can\'t create pdf thumbnails on item', [
				'item id'  => $e,
			]);
		}
	}

	protected function createThumbnail(&$media)
	{
		if (empty($media['url'])) return;

		$path = PdfThumbnailer::perform([
			'path' => $media['url'],
			'extension' => 'png'
		]);
		// must save on database
		$media['thumbnails'][] = $path;
	}
}
