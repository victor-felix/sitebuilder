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
			if ($validator->validate($visitor)->isValid()) {
				$this->repository->create($visitor);
				Session::writeFlash('success', s('Visitor successfully created.'));
				$this->redirect('/visitors');
			} else {
				Session::writeFlash('error', s('Sorry, we can\'t save the visitor'));
			}
		} 
	}

	public function reset($id)
	{
		$site = $this->getCurrentSite();
		$visitor = $this->repository->find($id);
		$password = $visitor->setRandomPassword();
		$this->repository->update($visitor);
		$data = [
			'title' => s('[%s]: New password', $site->title),
			'segment' => MeuMobi::currentSegment(),
			'password' => $password,
			'visitor' => $visitor,
			'site' => $site,
		];
		$mailer = new Mailer([
			'from' => $data['segment']->email,
			'to' => $visitor->email(),
			'subject' => $data['title'],
			'views' => ['text/html' => 'visitors/forgot_password_mail.htm'],
			'layout' => 'mail',
			'data' =>  $data,
		]);
		$mailer->send();
		Session::writeFlash('success', s('Visitor password successfully reseted.'));
		$this->redirect('/visitors');
	}

	public function remove($id)
	{
		$visitor = $this->repository->find($id);
		$this->repository->destroy($visitor);
		Session::writeFlash('success', s('Visitor successfully removed.'));
		$this->redirect('/visitors');
	}
}
