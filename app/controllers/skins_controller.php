<?php
use meumobi\sitebuilder\entities\Skin;
use meumobi\sitebuilder\repositories\SkinsRepository;

class SkinsController extends AppController
{
	protected $uses = array();

	public function delete($id = null)
	{
		$site = $this->getCurrentSite();
		$skinRepo = new SkinsRepository();
		$skin = $skinRepo->find($id);
		if ($skin->parentId()) {
			$site->skin = $skin->parentId();
		}
		if ($skinRepo->destroy($skin)) {
			$site->save();
			$message = 'successfully deleted.';
			if ($this->isXhr()) {
				$this->respondToJSON(array(
					'success' => $message,
				));
			} else {
				Session::writeFlash('success', $message);
				$this->redirect('/business_items/index/' . $item->parent_id);
			}
		}
	}
}