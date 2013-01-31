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
			$backupCreated = $this->createBackUpFolder();
			if ($backupCreated) {
				$this->removeImages();
				$this->recoverBackup();
			}
			echo "cleaned\n";
		} catch (Exception $e) {
			echo $e->getMessage(), "\n", "Can't clean images\n";
		}
		
	}
	
	protected function createBackUpFolder() {
		echo "Creating backup folder\n";
		
		$query = 'select path from images';
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
			$filepath = APP_ROOT .'/'. $item['path'];
			$copied = copy($filepath, $this->tmp_dir . $item['path']);
			if (!$this->params['forced'] && !$copied) {
				throw new Exception("Can't copy file: $filepath");
			}
		}
		
		return true;
	}
	
	protected function removeImages() {
		echo "Removing Images\n";
		foreach ($this->img_folders as $dir) {
			$path = APP_ROOT . '/' . $dir;
			exec("rm -rf $path");
		}
		
	}
	
	protected function recoverBackup() {
		echo "restoring backup images\n";
		$uploadsPath = APP_ROOT . '/uploads';

		exec("mv {$this->tmp_dir}uploads/* $uploadsPath");
	}
}

$cleaner = new Cleaner($argv);
$cleaner->clean();