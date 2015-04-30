<?php
use meumobi\sitebuilder\WorkerManager;
use meumobi\sitebuilder\workers\UpdateFeedsWorker;

require dirname(__DIR__) . '/config/cli.php';
// Enqueue if necessary
if (!WorkerManager::isEnqueued('update_feeds', ['priority' => UpdateFeedsWorker::PRIORITY_HIGH]))
	WorkerManager::enqueue('update_feeds', ['priority' => UpdateFeedsWorker::PRIORITY_HIGH]);

if (!WorkerManager::isEnqueued('update_feeds', ['priority' => UpdateFeedsWorker::PRIORITY_LOW]))
	WorkerManager::enqueue('update_feeds', ['priority' => UpdateFeedsWorker::PRIORITY_LOW]);
