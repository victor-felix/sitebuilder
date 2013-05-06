<?php
use meumobi\sitebuilder\entities\Skin;
use meumobi\sitebuilder\repositories\SkinsRepository;

class SkinsController extends AppController
{
	public function delete($id = null)
	{
		$skinRepo = new SkinsRepository();
		$skin = $skinRepo->find($id);
		if ($skinRepo->destroy($skin)) {
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