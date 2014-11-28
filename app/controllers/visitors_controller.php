<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

class VisitorsController extends AppController
{
	protected $uses = array();//prevent try to load Visitors model

	public function index()
	{
		$repository = new VisitorsRepository();
		$visitors = $repository->findBySiteId($this->getCurrentSite()->id);
		$this->set(compact('visitors'));
	}
}
