<?php
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
		id, site_id, parent_id, type, title, populate 
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
		id, site_id, parent_id, type, title, populate
	FROM categories AS ct
	WHERE NOT EXISTS (
	 SELECT id
		FROM sites AS st
		WHERE st.id = ct.site_id
	)
EOD;

		$invalidParents = Model::load('Categories')->query($invalidParentQuery);
		$this->logger()->info('invalid Categories', ['categories with invalid parents:' => $invalidParents]);

		$invalidSites = Model::load('Categories')->query($invalidSiteQuery);
		$this->logger()->info('invalid Categories', ['categories with invalid sites:' => $invalidSites]);
	}

	public function invalidFeeds() {
		$re = '/^(https?|feed):\/\/.[a-z0-9-_]+(\.[a-z0-9-]+)+([\/?].+)?$/mi';
		$invalidUrls = $invalidStatus = array();
		$extensions = Extensions::find('all', [
			'conditions' => ['extension' => 'rss'],
			'fields' => ['url', 'category_id','site_id'],
		]);

		foreach ( $extensions as $extension) {
			if (preg_match($re, $extension->url))
				$invalidUrls[] = $extension->to('array');

			list($version,$status,$msg) = explode(' ', get_headers($extension->url)[0]);
			if ($status != 200)
				$invalidStatus[$status][] = $extension->to('array');
		}

		$this->logger()->info('invalid Feeds', ['Feeds with invalid url:' => $invalidUrls]);
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
