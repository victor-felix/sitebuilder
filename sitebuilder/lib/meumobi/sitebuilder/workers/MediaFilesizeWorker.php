<?php

namespace meumobi\sitebuilder\workers;

use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;//TODO move exceptions for a more generic namespace

class MediaFilesizeWorker extends Worker
{
	public function perform()
	{
		$this->logger()->info('start add medias file size', [
			'item id'  => $this->getItem()->_id,
			]);
		try {
			foreach($this->getItem()->medias as $media) {
				$this->addFileSize($media);
			}
			//$this->getItem()->save();
			$this->logger()->info('Media file size successfully added', [
				'item id'  => $this->getItem()->_id,
				]);
		} catch(\Exception $e) {
			$this->logger()->info('Can\'t add file size on medias on item', [
				'item id'  => $this->getItem()->_id,
				]);
		}
	}

	protected function addFileSize($media)
	{
		if (!empty($media->length)) return;// go back if media have size
		$media->length = $this->getRemoteFileSize($media->url);
	}

	/**
	 * Returns the size of a file without downloading it, or 0 if the file
	 * size could not be determined.
	 */
	protected function getRemoteFileSize($url)
	{
		$curl = curl_init($url);
		// Issue a HEAD request and follow any redirects.
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_TIMEOUT,60);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$size = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($curl);

		return ($status == 200 || ($status > 300 && $status <= 308)) ? $size : 0;
	}
}
