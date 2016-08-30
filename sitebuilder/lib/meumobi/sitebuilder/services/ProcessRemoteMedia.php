<?php

namespace meumobi\sitebuilder\services;

use Exception;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\services\MediaThumbnailer;
use meumobi\sitebuilder\services\ProcessRemoteMedia\GenericMediaHandler;
use meumobi\sitebuilder\services\ProcessRemoteMedia\YoutubeHandler;

class ProcessRemoteMedia
{
	const COMPONENT = 'remote_media';

	public static $typeBlacklist = ['text/html'];

	public function schedule($item)
	{
		$itemArr = $item->to('array');
		$media = isset($itemArr['medias']) ? $itemArr['medias'] : [];
		$valid = array_filter($media, function ($medium) {
			return (!isset($medium['type']) || !$medium['type'])
				|| array_search($medium['type'], self::$typeBlacklist) === false;
		});

		if (count($valid)) {
			WorkerManager::enqueue('process_remote_media', [
				'item_id' => $item->id(),
			]);
		}
	}

	public function perform($item)
	{
		Logger::info(self::COMPONENT, 'processing remote media', [
			'item_id' => $item->id(),
		]);

		$successes = 0;
		$failures = 0;
		$skipped = 0;

		$media = array_map(function($medium) use ($item, &$successes, &$failures, &$skipped) {
			if (!isset($medium['url'])) {
				$skipped += 1;
				return $medium;
			}

			Logger::info(self::COMPONENT, 'requesting info for remote media', [
				'item_id' => $item->id(),
				'url' => $medium['url'],
				'type' => isset($medium['type']) ? $medium['type'] : null,
			]);

			$starttime = microtime(true);

			list($info, $error) = $this->getRemoteInfo($medium['url']);

			$endtime = microtime(true);
			$processingTime = $endtime - $starttime;

			if ($info) {
				foreach ($info as $key => $value) {
					$medium[$key] = $value ?: $medium[$key];
				}

				$successes += 1;

				$item->save();

				Logger::info(self::COMPONENT, 'remote media info downloaded', [
					'item_id' => $item->id(),
					'url' => $medium['url'],
					'info' => $info,
					'processing_time' => $processingTime,
				]);

				$medium = $this->createThumbnail($item, $medium);
			} else {
				$failures += 1;

				Logger::notice(self::COMPONENT, 'remote media info download failed', [
					'item_id' => $item->id(),
					'url' => $medium['url'],
					'error' => $error,
					'processing_time' => $processingTime,
				]);
			}

			return $medium;
		}, $item->to('array')['medias']);

		unset($item['medias']);
		$item->set([ 'medias' => $media ]);
		$item->save();

		Logger::info(self::COMPONENT, 'remote media processed', [
			'item_id' => $item->id(),
			'successes' => $successes,
			'failures' => $failures,
			'skipped' => $skipped,
		]);

		return $item;
	}

	public function createThumbnail($item, $medium)
	{
		$thumbnailer = new MediaThumbnailer;
		if ($thumbnailer->supportsType($medium['type'])) {
			$medium = $thumbnailer->perform($item, $medium);
		}

		return $medium;
	}

	protected function getRemoteInfo($url)
	{
		$handlers = [
			new YoutubeHandler(),
			new GenericMediaHandler(),
		];

		foreach ($handlers as $handler) {
			if (!$handler->match($url)) continue;

			return $handler->perform($url);
		}

		return [null, 'no handler found'];
	}
}
