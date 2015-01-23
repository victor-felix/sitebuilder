<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\presenters\VisitorGraphPresenter;
use meumobi\sitebuilder\presenters\AudienceReportPresenter;

class VisitorsController extends AppController
{
	protected $uses = array();//prevent try to load Visitors model

	public function index()
	{
		$repository = new VisitorsRepository();
		$visitors = $repository->findBySiteId($this->getCurrentSite()->id);
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
		$this->set(compact('visitors', 'report', 'visitorGraphDataJson')); }
}
