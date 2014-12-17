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
			$data['password'] = \Security::randomPassword();
			$data['site_id'] = $this->getSite()->id;
			$data['groups'] = $data['groups']
				? array_map('trim', explode(',', $data['groups']))
				: [];
			$visitor = new Visitor($data);
			$repo->create($visitor);
			$this->sendVisitorEmail($data);
			$imported++;
		}
		fclose($this->getFile());
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
		return $mailer->send();
	}
}
