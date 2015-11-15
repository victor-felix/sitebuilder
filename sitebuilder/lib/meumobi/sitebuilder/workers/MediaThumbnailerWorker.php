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
		$media = $item['medias']->to('array');

		foreach ($media as $medium) {
			if (!$this->supportsType($medium)) {
				continue;
			}

			if ($thumbnail = $this->createThumbnail($medium)) {
				$medium['thumbnails'] []= [ 'url' => $thumbnail ];

				$this->logger()->info('media thumbnail created', [
					'item_id'  => $this->getItem()->_id,
					'url' => $medium['url'],
				]);
			}
		}

		unset($item['medias']);
		$item['medias'] = $media;
		$item->save();

		$this->logger()->info('finish create pdf thumbnails', [
			'item_id'  => $this->getItem()->_id,
		]);
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

	protected function supportsType($medium) {
		if (isset($medium['type'])) {
			return array_search($medium['type'], MediaThumbnailer::supportedTypes()) >= 0;
		} else {
			// we don't know type yet, might as well try
			return true;
		}
	}
}
