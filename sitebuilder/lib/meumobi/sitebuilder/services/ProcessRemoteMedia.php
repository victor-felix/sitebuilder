<?php

namespace meumobi\sitebuilder\services;

use Exception;
use Filesystem;
use lithium\net\http\Response;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\services\MediaThumbnailer;

class ProcessRemoteMedia
{
	const COMPONENT = 'remote_media';

	public static $extensionToType = [
		'pdf' => 'application/pdf',
	];

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

			list($info, $status) = $this->getRemoteInfo($medium['url']);

			if ($info) {
				$medium['type'] = $info['type'] ?: $medium['type'];
				$medium['length'] = $info['length'] ?: $medium['length'];

				$successes += 1;

				Logger::info(self::COMPONENT, 'remote media info downloaded', [
					'item_id' => $item->id(),
					'url' => $medium['url'],
					'type' => $info['type'],
					'length' => $info['length'],
				]);

				$medium = $this->createThumbnail($item, $medium);
			} else {
				$failures += 1;

				Logger::notice(self::COMPONENT, 'remote media info download failed', [
					'item_id' => $item->id(),
					'url' => $medium['url'],
					'status' => $status,
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
		try {
			$thumbnailer = new MediaThumbnailer;
			$medium = $thumbnailer->perform($item, $medium);
		} catch (Exception $e) {
			Logger::error(self::COMPONENT, 'caught exception', [
				'message' => $e->getMessage(),
				'exception'  => $e,
			]);
		}

		return $medium;
	}

	protected function getRemoteInfo($url)
	{
		$info = [];

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_exec($curl);

		$response = new Response([
			'message' => curl_exec($curl)
		]);

		$status = $response->status['code'];
		$info['length'] = (int) $response->headers('Content-Length');
		$info['type'] = $this->getFileType($url, $response);

		curl_close($curl);

		return [
			($status == 200 ? $info : null),
			$status
		];
	}

	protected function getFileType($url, $response)
	{
		$type = null;

		if ($response->headers('Content-Type')) {
			list($type) = explode(';', $response->headers('Content-Type'));
		}

		if ($type == 'application/octet-stream' && $response->headers('Content-Disposition')) {
			$disposition = $response->headers('Content-Disposition');
			$match = preg_match('/filename="?([^"]+)"?/', $disposition, $matches);

			if ($match) {
				$type = $this->getFileTypeFromFilename($matches[1]);
			}
		}

		if (!$type) {
			$type = $this->getFileTypeFromFilename(basename($url));
		}

		return $type;
	}

	protected function getFileTypeFromFilename($filename)
	{
		$extension = Filesystem::extension($filename);

		return isset(self::$extensionToType[$extension])
			? self::$extensionToType[$extension]
			: null;
	}
}
