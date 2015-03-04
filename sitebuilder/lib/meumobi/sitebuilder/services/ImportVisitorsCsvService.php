<?php
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\repositories\RecordNotFoundException;

class ImportVisitorsCsvService extends ImportCsvService {
	protected $repository;
	protected $site;

	public function call() {
		//TODO implement service call
	}

	public function import()
	{
		$imported = 0;
		if (self::EXCLUSIVE == $this->method) {
			$this->clearVisitors();
		}
		while ($data = $this->getNextItem()) {
			$visitor = $this->getVisitor($data);
			if ($visitor->id()) {
				$this->logger()->info("updating visitor with email: {$visitor->email()}");
				$this->repository()->update($visitor);
			} else {
				$password = \Security::randomPassword();
				$visitor->setPassword($password);
				$this->logger()->info("creating visitor with email: {$visitor->email()} and password: $password");
				$this->repository()->create($visitor);
				$this->sendVisitorEmail(['email' => $visitor->email(), 'password' => $password]);
			}
			$imported++;
		}
		fclose($this->getFile());
		$this->logger()->info("total of imported visitors: $imported");
		return $imported;
	}

	public function setSite(\Sites $site)
	{
		$this->site = $site;
	}

	public function getSite()
	{
		if (!$this->site) {
			throw new \Exception("site not set");
		}
		return $this->site;
	}

	protected function clearVisitors()
	{
		$this->logger()->info("removing visitors from site: {$this->getSite()->id}");
		array_map(function($visitor) {
			$this->repository()->destroy($visitor);
		}, $this->repository()->findBySiteId($this->getSite()->id));
	}

	protected function getVisitor($data)
	{
		$visitor = null;
		$id = @$data['id'];
		unset($data['id']);
		if (self::EXCLUSIVE != $this->method && $id) {
			try {
				$visitor = $this->repository()->find($id);
				$visitor->setAttributes($data);
			} catch (RecordNotFoundException $e) {
			}
		}
		if (!$visitor) {
			$visitor = $this->buildVisitor($data);
		}
		return $visitor;
	}
	protected function buildVisitor($data)
	{
		$data['site_id'] = $this->getSite()->id;
		$data['groups'] = $data['groups'] ? $data['groups'] : [];
		return new Visitor($data);
	}
	protected function repository()
	{
		if ($this->repository) {
			return $this->repository;
		}
		return $this->repository = new VisitorsRepository();
	}

	protected function sendVisitorEmail($data)
	{
		\I18n::locale($this->getSite()->language);
		$segment = \MeuMobi::currentSegment();
		$data['title'] = s('[%s]: Get started', $this->getSite()->title);
		$data['segment'] = $segment;
		$data['site'] = $this->getSite();
		$mailer = new \Mailer(array(
			'from' => $segment->email,
			'to' => $data['email'],
			'subject' => $data['title'],
			'views' => array('text/html' => 'visitors/password_mail.htm'),
			'layout' => 'mail',
			'data' =>  $data,
		));
		$this->logger()->info("sending email to : {$data['email']}");
		return $mailer->send();
	}
