<?php
use meumobi\sitebuilder\Item;
use app\models\Items;

//set time limit to 240min
set_time_limit(60 * 240);

require dirname(dirname(__DIR__)) . '/config/bootstrap.php';
require 'config/settings.php';
require 'config/connections.php';
require 'app/models/images.php';

//get log instance
$log = \KLogger::instance(\Filesystem::path('log'));
$_ = array_shift($argv);

class Cleaner {
	protected $connection;
	protected $tmp_dir;
	protected $img_folders;
	protected $params;

	public function __construct($params = array()) {
		$this->params = $params;
		$this->params['forced'] = in_array('-f', $this->params);

		$this->connection = Connection::get('default');
		$this->tmp_dir = APP_ROOT . '/tmp/img_backup/';
		$this->img_folders = array('uploads/items', 'uploads/site_photos', 'uploads/site_logos');
	}

	public function clean() {
		try {
			$oldumask = umask(0);
			echo date('Y-m-d H:i:s'), " cleaning images\n";
			$imgsCopied = $this->copyAllImages();
			$backup = $this->createBackup();
			if ($backup && $imgsCopied) {
				//$this->removeImages();
				$this->recoverImages();
				$this->recreateThumbs();
			}
			umask($oldumask);
			echo date('Y-m-d H:i:s')," cleaned\n";
		} catch (Exception $e) {
			echo $e->getMessage(), "\n", "Can't clean images\n";
		}

	}

	protected function createBackup() {
		$backupFolder = APP_ROOT . '/tmp/imgs_backup_' .date('YmdHis');
		echo "Creating backup folder in $backupFolder\n";
		
		mkdir("$backupFolder/uploads",0777,true);
		foreach ($this->img_folders as $orgPath) {
			echo "Moving $orgPath folder to $backupFolder\n";
			$path = APP_ROOT .'/'. $orgPath;
			$moved = system("mv $path {$backupFolder}/{$orgPath}");
			if ($moved === false) {
				echo "Can't move $orgPath folder to $backupFolder\n";
				return false;
			}
		}
		return true;
	}

	protected function recreateThumbs() {
		echo "Regenerating thumbnails\n";
		passthru("php ".APP_ROOT."/sitebuilder/script/images/regenerate.php '*'");
	}

	protected function copyAllImages() {
		echo "Copying Images\n";
		$query = 'select id, foreign_key, path from images';
		//remove previus backup folder
		exec("rm -rf $this->tmp_dir");

		//create back folder
		foreach ($this->img_folders as $dir) {
			mkdir($this->tmp_dir . $dir, 0777, true);
		}
		$result = $this->connection->query($query);
		if (!$result) {
			throw new Exception('Cant list the images');
		}

		while ($item = $result->fetch()) {
			if ($this->isValidItem($item)) {
				$this->copyItemImage($item);
			}
		}

		return true;
	}

	protected function isValidItem($item, $removeIfInvalid = true) {
		if ($item['foreign_key']) {
			return true;
		}
		if ($removeIfInvalid) {
			$this->connection->query("DELETE FROM `images` WHERE id={$item['id']}");
		}
	}
	
	protected function copyItemImage($item) {
		$filepath = APP_ROOT .'/'. $item['path'];
		$copied = copy($filepath, $this->tmp_dir . $item['path']);
		if (!$copied) {
			if (!$this->params['forced']) {
			throw new Exception("Can't copy file: $filepath");
			} else {
				echo "Can't copy file: $filepath\n";
			}
		}
	}

	protected function removeImages() {
		echo "Removing Images\n";
		foreach ($this->img_folders as $dir) {
			$path = APP_ROOT . '/' . $dir;
			exec("rm -rf $path");
		}

	}

	protected function recoverImages() {
		echo "restoring images\n";
		$uploadsPath = APP_ROOT . '/uploads';

		exec("mv {$this->tmp_dir}uploads/* $uploadsPath");
	}
}

$cleaner = new Cleaner($argv);
$cleaner->clean();