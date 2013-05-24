<?php
use meumobi\sitebuilder\repositories\ThemesRepository;
use meumobi\sitebuilder\repositories\SkinsRepository;
use meumobi\sitebuilder\entities\Theme;
use meumobi\sitebuilder\entities\Skin;


class SkinsController extends AppController
{
	protected $uses = array('users');
	protected $themeRepo;
	protected $skinRepo;

	protected function beforeFilter()
	{
		if (Users::ROLE_ADMIN != $this->getCurrentSite()->role) {
			$this->redirect('/');
		}
		$this->themeRepo = new ThemesRepository();
		$this->skinRepo = new SkinsRepository();
		$this->set(array(
			'site' => $this->getCurrentSite()
		));
	}

	public function add($themeId)
	{
		$theme = $this->themeRepo->find($themeId);
		$skinData = array(
			'theme_id' => $theme->id(),
			'main_color' => $theme->defaults('main_color'),
			'colors' => $theme->defaults(),
		);
		if (!empty($this->data)) {
			$this->data['main_color'] = substr($this->data['main_color'],1);
			$skinData = array_merge($skinData, $this->data);
			$skin = new Skin($skinData);
			$this->skinRepo->create($skin);
			Session::writeFlash('success', s('Configuration successfully saved'));
			if ($this->data['continue']) {
				$this->redirect("/skins/edit/{$skin->id()}");
			} else {
				$this->redirect('/');
			}
		} else {
			$skin = new Skin($skinData);
		}
		$this->set(compact('theme', 'skin'));
	}

	public function edit($id)
	{
		$skin = $this->skinRepo->find($id);
		$theme = $this->themeRepo->find($skin->themeId());
		if (!empty($this->data)) {
			$this->data['main_color'] = substr($this->data['main_color'],1);
			$skin->setAttributes($this->data);
			$this->skinRepo->update($skin);
			Session::writeFlash('success', s('Configuration successfully saved'));
			if (!$this->data['continue']) {
				$this->redirect('/');
			}
		}
		$this->set(compact('theme', 'skin'));
	}

	public function copy($id)
	{
		$skin = $this->skinRepo->find($id);
		$skinData = array(
			'theme_id' => $skin->themeId(),
			'main_color' => $skin->mainColor(),
			'colors' => $skin->colors(),
		);
		$newSkin = new Skin($skinData);
		$this->skinRepo->create($newSkin);
		Session::writeFlash('success', s('Skin copied successfully.'));
		$this->redirect("/skins/edit/{$newSkin->id()}");
	}

	public function delete($id)
	{
		$skin = $this->skinRepo->find($id);
		if ($this->skinRepo->destroy($skin)) {
			$message = 'successfully deleted.';
			if ($this->isXhr()) {
				$this->respondToJSON(array(
					'success' => $message,
				));
			} else {
				Session::writeFlash('success', $message);
				$this->redirect('/');
			}
		}
	}

	public function delete_custom($id)
	{
		$site = $this->getCurrentSite();
		$skin = $this->skinRepo->find($id);
		if ($skin->parentId()) {
			$site->skin = $skin->parentId();
		}
		if ($this->skinRepo->destroy($skin)) {
			$site->save();
			$message = 'successfully deleted.';
			if ($this->isXhr()) {
				$this->respondToJSON(array(
					'success' => $message,
				));
			} else {
				Session::writeFlash('success', $message);
				$this->redirect('/');
			}
		}
	}
}