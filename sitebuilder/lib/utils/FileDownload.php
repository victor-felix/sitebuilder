<?php

class FileDownload
{
	public $path;

	public function download($file, $name, $path = null)
	{
		if (is_null($path)) {
			$path = $this->path;
		}

		Filesystem::createDir($path, 0755);

		if( Filesystem::hasPermission($path, 'w')) {
			$name = self::getName($file, $name);

			$destination = $path . '/' . $name;
			if (!Filesystem::exists($destination)) {
				self::downloadFile($file, $destination);
				return $name;
			} else {
				throw new Exception('destination file already exists');
			}
		} else {
			throw new Exception('download folder is not writeable');
		}
	}

	public static function getName($file, $name)
	{
		$parsed_url = parse_url(htmlspecialchars_decode($file));
		$file = basename($parsed_url['path']);

		return String::insert($name, array(
			'extension' => Filesystem::extension($file),
			'name' => Filesystem::filename($file),
			'original_name' => $file
		));
	}

	protected function downloadFile($source, $target)
	{
		$remote = curl_init($source);
		$local = fopen(Filesystem::path($target), 'wb');

		curl_setopt($remote, CURLOPT_FILE, $local);
		curl_setopt($remote, CURLOPT_HEADER, 0);
		curl_setopt($remote, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($remote, CURLOPT_TIMEOUT, 60);
		curl_exec($remote);
		$status = curl_getinfo($remote, CURLINFO_HTTP_CODE);
		curl_close($remote);
		fclose($local);

		if ($status >= 300) {
			Filesystem::delete($target);
			throw new Exception("source file returned status $status");
		}
	}
}
