<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\presenters\VisitorGraphPresenter;
use meumobi\sitebuilder\presenters\AudienceReportPresenter;
use meumobi\sitebuilder\validators\VisitorsPersistenceValidator;

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
		$data = $this->data;
		$data['site_id'] = $site->id();
		$visitor = new Visitor($data);
		if (!empty($this->data)) {
			$data['password'] = $visitor->setRandomPassword();
			$validator = new VisitorsPersistenceValidator();
			if ($validator->validate($visitor)->isValid()) {
				$this->repository->create($visitor);
				$data['title'] = s('[%s]: Get started', $site->title);
				$this->sendVisitorEmail($data);
				Session::writeFlash('success', s('Visitor successfully created.'));
				$this->redirect('/visitors');
			} else {
				Session::writeFlash('error', s('Sorry, we can\'t save the visitor'));
			}
		}
		$this->set(compact('visitor', 'site'));
	}

	public function edit($id)
	{
		$site = $this->getCurrentSite();
		$visitor = $this->repository->find($id);
		if (!empty($this->data)) {
			$visitor->setAttributes($this->data);
			$validator = new VisitorsPersistenceValidator();
			if ($validator->validate($visitor)->isValid()) {
				$this->repository->update($visitor);
				Session::writeFlash('success', s('Visitor successfully updated.'));
				$this->redirect('/visitors');
			} else {
				Session::writeFlash('error', s('Sorry, we can\'t update the visitor'));
			}
		}
		$this->set(compact('visitor', 'site'));
	}

	public function reset($id)
	{
		$site = $this->getCurrentSite();
		$visitor = $this->repository->find($id);
		$password = $visitor->setRandomPassword();
		$this->repository->update($visitor);
		$data = [
			'title' => s('[%s]: New password', $site->title),
			'password' => $password,
			'email' => $visitor->email(),
			'visitor' => $visitor,
		];
		$this->sendVisitorEmail($data, 'visitors/forgot_password_mail.htm');
		Session::writeFlash('success', s('Visitor password successfully renewed.'));
		$this->redirect('/visitors');
	}

	public function delete($id)
	{
		$visitor = $this->repository->find($id);
		$this->repository->destroy($visitor);
		Session::writeFlash('success', s('Visitor successfully removed.'));
		$this->redirect('/visitors');
	}

	protected function sendVisitorEmail($data, $template = 'visitors/password_mail.htm')
	{
		$segment = \MeuMobi::currentSegment();
		$data['segment'] = $segment;
		$data['site'] = $this->getCurrentSite();
		$mailer = new \Mailer([
			'from' => $segment->email,
			'to' => $data['email'],
			'subject' => $data['title'],
			'views' => array('text/html' => $template),
			'layout' => 'mail',
			'data' =>  $data,
		]);
		return $mailer->send();
	}
}
