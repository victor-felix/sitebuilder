<?php
use app\models\Extensions;
require dirname(__DIR__) . '/config/cli.php';

class CheckDatabase
{
	protected $logger;

	public function check() {
		$this->invalidCategories();
		$this->invalidFeeds();
		//$this->sitelessUsers()
		//$this->invalidSiteUsersRelations()
		//$this->invalidSitesSegment()
	}

	public function invalidCategories()
	{
		$invalidParentQuery = <<<'EOD'
	SELECT 
		id, site_id, parent_id, type
	FROM categories AS ct
	WHERE parent_id IS NOT NULL 
	AND NOT EXISTS (
		SELECT id
		FROM categories AS ct2
		WHERE ct2.id = ct.parent_id
	)
EOD;

		$invalidSiteQuery = <<<'EOD'
	SELECT
		id, site_id, parent_id, type
	FROM categories AS ct
	WHERE NOT EXISTS (
	 SELECT id
		FROM sites AS st
		WHERE st.id = ct.site_id
	)
EOD;

		$invalidParents = Model::load('Categories')->query($invalidParentQuery);
		if ($invalidParents->rowCount()) {
			$this->logger()->info('invalid Categories', ['categories with invalid parents:' => $invalidParents]);
		} else {
			$this->logger()->info('No Categories with invalid parents');
		}

		$invalidSites = Model::load('Categories')->query($invalidSiteQuery);
		if ($invalidSites->rowCount()) {
			$this->logger()->info('invalid Categories', ['categories with invalid sites:' => $invalidSites]);
		} else {
			$this->logger()->info('No Categories with invalid sites');	
		}
	}

	public function invalidFeeds() {
		$re = '/^(https?|feed):\/\/.[a-z0-9-_]+(\.[a-z0-9-\/]+)+([\/?].+)?$/mi'; //'/^(https?|feed):\/\/.[a-z0-9-_]+(\.[a-z0-9-]+)+([\/?].+)?$/mi';
		$invalidUrlFormats = $invalidRequest =  $invalidStatus = array();
		$extensions = Extensions::find('all', [
			'conditions' => ['extension' => 'rss', 'enabled' => 1],
			'fields' => ['url'],
		]);

		foreach ($extensions as $extension) {
			if (!$extension->url || !preg_match($re, $extension->url)) {
				$invalidUrls[] = $extension->to('array');
			} else {
				$headers = get_headers(str_replace('feed://', 'http://', $extension->url));
				if ($headers) {
					list($version,$status,$msg) = explode(' ', $headers[0]);
					if ($status != 200) {
						$invalidStatus["status_$status"][] = $extension->to('array');
					}
				} else {
					$invalidRequest[] = $extension->to('array');
				}
			}
		}

		if (isset($invalidUrls))
			$this->logger()->info('invalid Feeds', ['Feeds with invalid url format:' => $invalidUrls]);
		if (isset($invalidRequest))
			$this->logger()->info('invalid Feeds', ['Feeds with invalid request:' => $invalidRequest]);
		if (isset($invalidStatus))
			$this->logger()->info('invalid Feeds', ['Feeds with invalid response status(obs. grouped by status):' => $invalidStatus]);
	}

	protected function logger()
	{
		if ($this->logger)
			return $this->logger;
		$handler = new \Monolog\Handler\RotatingFileHandler(APP_ROOT . '/log/check_database.log');
		$logger = new \Monolog\Logger('sitebuilder.check_database', [$handler]);
		return $this->logger = $logger;
	}


}

$_ = array_shift($argv);
$checkDatabase = new CheckDatabase();
if ($argv) {
	foreach ($argv as $check) {
		if (method_exists($checkDatabase, $check))
			$checkDatabase->$check();
	}
} else {
	$checkDatabase->check();
}
