<?php

namespace app\controllers\api;

use meumobi\sitebuilder\presenters\api\SitePresenter;
use meumobi\sitebuilder\presenters\api\SkinPresenter;
use meumobi\sitebuilder\repositories\SkinsRepository;

class SitesController extends ApiController
{
	protected $skipBeforeFilter = ['requireVisitorAuth'];

	public function show()
	{
		return $this->toJSON($this->site());
	}

	public function performance()
	{
		return SitePresenter::present($this->site(), $this->param('skin'));
	}

	public function theme()
	{
		$skinsRepo = new SkinsRepository();
		$skin = $skinsRepo->find($this->site()->skin);
		return SkinPresenter::present($skin);
	}
}
