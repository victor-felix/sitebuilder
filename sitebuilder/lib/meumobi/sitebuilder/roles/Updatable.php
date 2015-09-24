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
			$category->site();

			return $category;
		} catch (RecordNotFoundException $e) {
			$this->disableExtension($extension, $e->getMessage());

			throw new Exception($e->getMessage());
		}
	}

	public function disableExtension($extension, $reason)
	{
		$extension->enabled = 0;
		$extension->save();

		Logger::info('extensions', 'category extension disabled', [
			'extension_id' => (string) $extension->_id,
			'category_id' => $extension->category_id,
			'site_id' => $extension->site_id,
			'reason' => $reason,
		]);
	}
}
