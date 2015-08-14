<?php

class CheckImportVisitors
{
	public function check($importPath, $logPath, $reportPath, $verbose)
	{
		if (!$this->checkFilesAreReadable([$importPath, $logPath])) {
			return;
		}

		$importFile = fopen($importPath, 'r');
		$reportFile = fopen($reportPath, 'w');

		$fields = array_map(function($field) {
			return trim($field);
		}, fgetcsv($importFile, 1000));

		$emailKey = array_search('email', $fields);

		fputcsv($reportFile, array_merge($fields, ['date', 'status']));

		while (($line = fgetcsv($importFile, 1000)) !== false) {
			$email = trim($line[$emailKey]);
			$logLine = $this->findLogByEmail($email, $logPath);

			if ($logLine) {
				list($date, $status) = $this->extractDataFromLog($logLine);
				$line[] = $date;
				$line[] = $status;
				$this->log("Found in log email: $email, status: $status, date: $date", $verbose);
			} else {
				$line[] = '';
				$line[] = '';
				$this->log("Missing in log email: $email", $verbose);
			}

			fputcsv($reportFile, $line);
		}

		fclose($importFile);
		fclose($reportFile);
	}

	protected function findLogByEmail($email, $path)
	{
		exec("grep -E 'sm-mta.*to=<$email>' $path | tail -1", $result);
		return $result ? $result[0] : null;
	}

	protected function extractDataFromLog($logLine)
	{
		// Matches date / status
		$re = '/^([A-Za-z]+ +\d{1,2} +\d{2}:\d{2}:\d{2}).*stat=(\w+( \w+)?)/mi';
		preg_match_all($re, $logLine, $matches);
		// Removes first/last unecessary match
		array_shift($matches);
		array_pop($matches);

		return array_map(function($row) {
			return $row[0];
		}, $matches);
	}

	protected function checkFilesAreReadable($files)
	{
		return array_reduce($files, function($result, $path) {
			$readable = is_readable($path);
			if (!$readable) {
				$this->log("File $path can`t be read");
			}
			return $result && $readable;
		}, true);
	}

	protected function log($message, $verbose = true)
	{
		if ($verbose) {
			echo "$message\n";
		}
	}
}

$options = getopt('', ['import-file:', 'log-file:', 'report-file:', 'verbose']);

if (isset($options['import-file'])) {
	$importPath = $options['import-file'];
	$logPath = isset($options['log-file']) ? $options['log-file'] : '/var/log/mail.log';
	$reportPath = isset($options['report-file']) ? $options['report-file'] : dirname(dirname(__DIR__)) . '/log/import_visitors_report.csv';
	$verbose = isset($options['verbose']);
	$checkImportVisitors = new CheckImportVisitors();

	$checkImportVisitors->check($importPath, $logPath, $reportPath, $verbose);
} else {
	echo <<<'EOL'
	usage: php check_import_visitors.php OPTIONS

	Add status and date of invite email sent to imported visitors

	OPTIONS:
		MANDATORY:
			--import-file file path

		OPTIONAL:
			--log-file file path, default: /var/log/mail.log
			--report-file file path, default: log/import_visitors_report.csv
			--verbose print log messages

	EXAMPLE:
	$php sitebuilder/script/check_import_visitors.php --verbose --import-file tmp/visitors.csv

EOL;
}
