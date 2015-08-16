<?php

namespace meumobi\sitebuilder\workers;

use app\models\Items;
use meumobi\sitebuilder\services\MediaThumbnailer;

class MediaThumbnailerWorker extends Worker
{
	public function perform()
	{
		$this->logger()->info('start create pdf thumbnails', [
			'item_id'  => $this->getItem()->_id,
		]);

		//must use array to prevent a lithium persistence bug
		$item = $this->getItem()->to('array');
		array_walk($item['medias'],[$this, 'createThumbnail']);

		$this->getItem()->medias = $item['medias'];
		$this->getItem()->save();

		$this->logger()->info('Media thumbnails created', [
			'item_id'  => $this->getItem()->_id,
		]);
	}

	protected function createThumbnail(&$media)
	{
		try {
			$path = MediaThumbnailer::perform($media['url']);
			$media['thumbnails'][] = $path;
		} catch(\Exception $e) {
			$this->logger()->info('Can\'t create thumbnail on item', [
				'exception'  => $e,
				'file' => $media['url'],
				'item_id' => $this->getItem()->_id
			]);
		}
	}
}
