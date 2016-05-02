<?php
namespace meumobi\sitebuilder\roles;

use Exception;
use Inflector;
use app\models\Extensions;
use meumobi\sitebuilder\repositories\RecordNotFoundException;
use meumobi\sitebuilder\Logger;

trait Updatable
{
	public function getExtensionsByPriorityAndType($priority, $type)
	{
		$classname = '\app\models\extensions\\' . Inflector::camelize($type);

		return $classname::find('all', [
			'conditions' => [
				'extension' => $type,
				'enabled' => 1,
				'priority' => $priority,
			],
		]);
	}

	public function getCategory($extension)
	{
		try {
			$category = Extensions::category($extension);

			$this->assertValidSite($category, $extension);

			return $category;
		} catch (RecordNotFoundException $e) {
			Logger::info('extensions', 'category of extension not found', [
				'extension_id' => (string) $extension->_id,
				'category_id' => $extension->category_id,
				'site_id' => $extension->site_id,
			]);

			$this->disableExtension($extension, $message);

			throw new Exception($message);
		}
	}


	public function assertValidSite($category, $extension)
	{
		try {
			$category->site();
		} catch (RecordNotFoundException $e) {
			$message = 'site of extension not found';

			Logger::info('extensions', $message, [
				'extension_id' => (string) $extension->_id,
				'category_id' => $extension->category_id,
				'site_id' => $extension->site_id,
			]);

			$this->disableExtension($extension, $message);

			throw new Exception($message);
		}
	}

	public function disableExtension($extension, $reason)
	{
		$extension->enabled = 0;
		$extension->save(null, ['callbacks' => false]);

		Logger::info('extensions', 'extension disabled', [
			'extension_id' => (string) $extension->_id,
			'category_id' => $extension->category_id,
			'site_id' => $extension->site_id,
			'reason' => $reason,
		]);
	}

}
