<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\presenters\VisitorGraphPresenter;
use meumobi\sitebuilder\presenters\AudienceReportPresenter;

class VisitorsController extends AppController
{
	protected $uses = array();//prevent try to load Visitors model
	protected $repository;

	protected function beforeFilter()
	{
		parent::beforeFilter();
		$this->repository = new VisitorsRepository();
	}

	public function index()
	{
		$visitors = $this->repository->findBySiteId($this->getCurrentSite()->id);
		$report = AudienceReportPresenter::present($visitors);
		$visitorGraphData = [
			'versions-graph' => $report['appVersions'],
			'subscribed-graph' => [
				'Subscribed' => $report['subscribedPercent'],
				'Unsubscribed' => $report['unsubscribedPercent'],
			],
			'accepted-graph' => [
				'Accepted' => $report['accepted'],
				'Pending' => $report['pending']
			]
		];
		$visitorGraphDataJson = VisitorGraphPresenter::present($visitorGraphData);
		$this->set(compact('visitors', 'report', 'visitorGraphDataJson')); 
	}

	public function add()
	{
		$site = $this->getCurrentSite();
		$visitor = new Visitor($this->data);
		if (!empty($this->data)) {
			$validator = new VisitorPersistenceValidator();
			if ($validator->isValid($visitor)) {
				$this->repository->create($visitor);
				if ($this->isXhr()) {
					$this->respondToJSON(array(
						'go_back' => true,
						'refresh' => '/visitorss',
						'success' => s('Visitor successfully created.')
					));
				} else {
					Session::writeFlash('success', s('Visitor successfully created.'));
					$this->redirect('/visitors');
				}
			} else {
				if ($this->isXhr()) {
					$this->respondToJSON(array(
						'refresh' => '/visitors/add/',
						'error' => s('Sorry, we can\'t save the visitor')
					));
				}
			}
		} 
	}
}
