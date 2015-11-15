<?php

namespace meumobi\sitebuilder\workers;

use Exception;
use app\models\Items;
use meumobi\sitebuilder\services\MediaThumbnailer;

class MediaThumbnailerWorker extends Worker
{
	public function perform()
	{
		$this->logger()->info('start create pdf thumbnails', [
			'item_id'  => $this->getItem()->_id,
		]);

		$item = $this->getItem();

		foreach ($item['medias'] as $medium) {
			if ($thumbnail = $this->createThumbnail($medium)) {
				$medium['thumbnails'] []= $thumbnail;

				$this->logger()->info('media thumbnail created', [
					'item_id'  => $this->getItem()->_id,
					'url' => $medium['url'],
				]);
			}
		}

		$item->medias = $item['medias'];
		$item->save();
	}

	protected function createThumbnail($media)
	{
		try {
			return MediaThumbnailer::perform($media['url']);
		} catch(Exception $e) {
			$this->logger()->info('cannot create thumbnail on item', [
				'exception'  => $e,
				'url' => $media['url'],
				'item_id' => $this->getItem()->_id
			]);

			return false;
		}
	}
}
