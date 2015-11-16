<?php
use lithium\data\Connections;

require_once dirname(__DIR__) . '/config/bootstrap.php';
require_once 'config/settings.php';
require_once 'config/connections.php';

class Backup
{
	public static function mysql($config) {
		$path = self::getPath('mysql.sql');
		system("mysqldump -v --user={$config['user']} --password={$config['password']} --host={$config['host']} {$config['database']} > $path");
	}

	public static function mongodb($config) {
		$path = self::getPath('mongodb');
		system("mongodump --host {$config['host']} -d {$config['database']}  -o $path");
	}

	protected static function getPath($file) {

		$path = dirname(__DIR__) . '/db/backups/';
		if(!file_exists($path))
			mkdir($path, 0777, true);//create if necessary
		return $path . $file;
	}
}

$mysqlConfig = Connection::config('default');
$mongoConfig = Connections::get('default', ['config' => true]);

Backup::mysql($mysqlConfig);
Backup::mongodb($mongoConfig);

exit(0);
