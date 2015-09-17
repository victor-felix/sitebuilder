<?php
namespace meumobi\sitebuilder\services;

use Model;
use Users;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Skin;
use meumobi\sitebuilder\repositories\SkinsRepository;
use meumobi\sitebuilder\repositories\VisitorsRepository;

class RemoveSite
{
	public function  remove($site, $user = null)
	{
		if ($user && !$this->canRemove($site, $user)) return;

		$this->removeVisitors($site);
		$this->removeUsers($site);
		$this->removeCategories($site);
		$this->removeCustomSkin($site);
		$this->removeImages($site);


		$deleted = $site->delete($site->id);

		if ($deleted) {
			$logMessage = 'site removed';
		} else {
			$logMessage = 'site not removed';
		}

		Logger::info('sites', $logMessage, [
			'site_id' => $site->id,
			'user_id' => $user ? $user->id : '',
		]);

		return $deleted;
	}

	protected function canRemove($site, $user)
	{
		$isAdmin = $user->role($site) == Users::ROLE_ADMIN;

		return $isAdmin && count($user->sites()) > 1;
	}

	protected function removeVisitors($site)
	{
		$repository = new VisitorsRepository();
		$visitors = $repository->findBySiteId($site->id);

		$removed = array_map(function($visitor) use ($repository) {
			$email = $visitor->email();
			$repository->destroy($visitor);

			return $email;
		}, $visitors);
	}

	protected function removeUsers($site)
	{
		Model::load('UsersSites')->removeSite($site->id);
	}

	protected function removeCategories($site)
	{
		$model = Model::load('Categories');
		$this->deleteSet($model, $model->all([
			'conditions' => [
				'site_id' => $site->id,
				'parent_id' => null
			]
		]));
	}

	protected function removeCustomSkin($site)
	{
		$skin = $site->skin();

		if ($skin && $skin->parentId()) {
			$skinRepo = new SkinsRepository();
			$skinRepo->destroy($skin);
		}
	}

	protected function removeImages($site)
	{
		$model = Model::load('Images');
		$types = ['SiteLogos', 'SitePhotos', 'SiteSplashScreens', 'SiteAppleTouchIcon'];

		array_walk($types, function($type) use ($model, $site) {
			$images = $model->allByRecord($type, $site->id);

			$this->deleteSet($model, $images);
		});
	}

	protected function deleteSet($model, $set) {
		foreach($set as $item) {
			$model->delete($item->id);
		}
	}
}
