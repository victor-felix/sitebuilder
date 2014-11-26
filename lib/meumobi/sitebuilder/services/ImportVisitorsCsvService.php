<?php
namespace meumobi\sitebuilder\services;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\VisitorsRepository;

class ImportVisitorsCsvService extends ImportCsvService {
	const LOG_CHANNEL = 'sitebuilder.import_visitors_csv';

	protected $site;

	public function call() {
		//TODO implement service call
	}

	public function import()
	{
		$startTime = time();
		$imported = 0;
		$repo = new VisitorsRepository();
		while ($data = $this->getNextItem()) {
			$data['password'] = 'infobox';//TODO auto generate and send the password by email
			$data['site_id'] = $this->getSite()->id;
			$data['groups'] = $data['groups'] ? explode(',', $data['groups']) : [];//if groups is set explode, otherwise empty array
			$visitor = new Visitor($data);
			$repo->create($visitor);
			$this->sendVisitorEmail($data);
			$imported++;
		}
		fclose($this->getFile());
		//unlink($this->filePath);
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

	protected function sendVisitorEmail($data) {
		//if (\Config::read('Mail.preventSending'))
			//return;
		$segment = \MeuMobi::currentSegment();
		$data['segment'] = $segment;
		$mailer = new \Mailer(array(
			'from' => $segment->email,
			'to' => $data['email'],
			'subject' => 'Visitor Password',
			'views' => array('text/html' => 'visitors/password_mail.htm'),
			'layout' => 'mail',
			'data' =>  $data,
		)); 
		return $mailer->send();
	}
}
