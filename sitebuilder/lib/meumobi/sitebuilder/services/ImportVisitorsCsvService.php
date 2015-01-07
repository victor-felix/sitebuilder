<?php
namespace meumobi\sitebuilder\services;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\repositories\RecordNotFoundException;

class ImportVisitorsCsvService extends ImportCsvService {
	const LOG_CHANNEL = 'sitebuilder.import_visitors_csv';

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
				$this->log("updating visitor with email: {$visitor->email()}");
				$this->repository()->update($visitor);
			} else {
				$password = \Security::randomPassword();
				$visitor->setPassword($password);
				$this->log("creating visitor with email: {$visitor->email()} and password: $password");
				$this->repository()->create($visitor);
				$this->sendVisitorEmail(['email' => $visitor->email(), 'password' => $password]);
			}
			$imported++;
		}
		fclose($this->getFile());
		$this->log("total of imported visitors: $imported");
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
		$this->log("removing visitors from site: {$this->getSite()->id}");		
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
		$this->log("sending email to : {$data['email']}");
		return $mailer->send();
	}

	protected function log($message)
	{
		//TODO improve this
		echo "$message\n";
	}
}
