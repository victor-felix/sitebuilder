<?php

use meumobi\sitebuilder\Logger;

require dirname(__DIR__) . '/config/cli.php';

function check($importPath, $logPath, $reportPath)
{
	$importFile = fopen($importPath, 'r');
	$reportFile = fopen($reportPath, 'w');

	$fields = array_map(function($field) {
		return trim($field);
	}, fgetcsv($importFile, 1000));

	$emailKey = array_search('email', $fields);

	fputcsv($reportFile, array_merge($fields, ['date', 'status']));

	while (($line = fgetcsv($importFile, 1000)) !== false) {
		$email = trim($line[$emailKey]);
		$logLine = findLogByEmail($email, $logPath);

		if ($logLine) {
			list($date, $status) = extractDataFromLog($logLine);
			$line[] = $date;
			$line[] = $status;
			Logger::info('visitors', 'visitor invite email status', compact('email', 'status', 'date'));
		} else {
			Logger::info('visitors', 'visitor invite email not found in log', compact('email'));
		}

		fputcsv($reportFile, $line);
	}

	fclose($importFile);
	fclose($reportFile);
}

function findLogByEmail($email, $path)
{
	exec("grep -E 'sm-mta.*$email' $path | tail -1", $result);
	return $result ? $result[0] : null;
}

function extractDataFromLog($logLine)
{
	// Matches date / status
	$re = '/^(\w{3}  \d \d{2}:\d{2}:\d{2}).*stat=(\w+( \w+)?)/mi';
	preg_match_all($re, $logLine, $matches);
	// Removes first/last unecessary match
	array_shift($matches);
	array_pop($matches);

	return array_map(function($row) {
		return $row[0];
	}, $matches);
}

function checkFilesAreReadable($files)
{
	return array_reduce($files, function($result, $path) {
		$readable = is_readable($path);
		if (!$readable) {
			echo "File $path can`t be read\n";
		}
		return $result && $readable;
	}, true);
}

$options = getopt('', ['import-file:', 'log-file:', 'report-file:']);

if (isset($options['import-file'])) {
	$importPath = $options['import-file'];
	$logPath = isset($options['log-file']) ? $options['log-file'] : '/var/log/mail.log';
	$reportPath = isset($options['report-file']) ? $options['report-file'] : APP_ROOT . '/log/import_visitors_report.csv';

	if (!checkFilesAreReadable([$importPath, $logPath])) {
		return;
	}

	check($importPath, $logPath, $reportPath);
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

	EXAMPLE:
	$php sitebuilder/script/check_import_visitors.php --import-file tmp/visitors.csv

EOL;
}
