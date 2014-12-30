<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\presenters\api\AudienceReportPresenter;

class VisitorsController extends AppController
{
	protected $uses = array();//prevent try to load Visitors model

	public function index()
	{
		$repository = new VisitorsRepository();
		$visitors = $repository->findBySiteId($this->getCurrentSite()->id);
		$report = AudienceReportPresenter::present($visitors);
		$this->set(compact('visitors', 'report'));
	}
}
