<?php

namespace meumobi\sitebuilder\services;

use Exception;
use Filesystem;
use Mimey\MimeTypes;
use lithium\net\http\Response;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\services\MediaThumbnailer;

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

			list($info, $status) = $this->getRemoteInfo($medium['url']);

			$endtime = microtime(true);
			$processingTime = $endtime - $starttime;

			if ($info) {
				$medium['type'] = $info['type'] ?: $medium['type'];
				$medium['length'] = $info['length'] ?: $medium['length'];

				$successes += 1;

				$item->save();

				Logger::info(self::COMPONENT, 'remote media info downloaded', [
					'item_id' => $item->id(),
					'url' => $medium['url'],
					'type' => $info['type'],
					'length' => $info['length'],
					'processing_time' => $processingTime,
				]);

				$medium = $this->createThumbnail($item, $medium);
			} else {
				$failures += 1;

				Logger::notice(self::COMPONENT, 'remote media info download failed', [
					'item_id' => $item->id(),
					'url' => $medium['url'],
					'status' => $status,
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
		$info = [];
		$headers = '';

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RANGE, '0-0');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_HEADERFUNCTION, function($curl, $data) use (&$headers) {
			$headers .= $data;
			$length = strlen($data);
			return $length > 2 ? $length : 0;
		});

		curl_exec($curl);
		curl_close($curl);

		$response = new Response([
			'message' => $headers,
		]);

		$status = $response->status['code'];
		$info['type'] = $this->getFileType($url, $response);

		if (($length = $response->headers('Content-Length')) > 1) {
			$info['length'] = (int) $length;
		} else if (preg_match('/bytes 0-0\/(\d+)/', $response->headers('Content-Range'), $matches)) {
			$info['length'] = (int) $matches[1];
		}

		return [
			($status >= 200 && $status < 300 ? $info : null),
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
		$mimes = new MimeTypes;

		return $mimes->getMimeType($extension);
	}
}
