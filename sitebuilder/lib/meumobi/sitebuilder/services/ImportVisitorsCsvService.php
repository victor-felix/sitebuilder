<?php

namespace meumobi\sitebuilder\services;

use Exception;
use I18n;
use Mailer;
use MeuMobi;
use Sites;
use meumobi\sitebuilder\Logger;
use meumobi\sitebuilder\entities\Visitor;
use meumobi\sitebuilder\repositories\RecordNotFoundException;
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\validators\VisitorsPersistenceValidator;

class ImportVisitorsCsvService extends ImportCsvService
{
	protected $repository;
	protected $site;
	protected $passwordStrategy = VisitorPasswordGenerationService::DEFAULT_PASSWORD;

	public function import($options)
	{
		$imported = 0;
		$resend = $options['resend'];
		$passwordGenerationService = new VisitorPasswordGenerationService();
		$validator = new VisitorsPersistenceValidator();

		Logger::info('visitors', 'start importing visitors');

		if (self::EXCLUSIVE == $this->method) {
			$this->clearVisitors();
		}

		while ($data = $this->getNextItem()) {
			$visitor = $this->getVisitor($data);
			$validationResult = $validator->validate($visitor);

			if (!$validationResult->isValid()) {
				Logger::error('visitors', 'visitor can`t be imported', [
					'errors' => $validationResult->errors(),
					'visitor' => $data,
				]);
				continue;
			}

			if ($visitor->id()) {
				$this->updateVisitor($visitor, $passwordGenerationService, $resend);
			} else {
				$this->createVisitor($visitor, $passwordGenerationService);
			}

			$imported++;
		}

		fclose($this->getFile());
		Logger::info('visitors', 'imported visitors', ['total' => $imported]);

		return $imported;
	}

	public function setSite(Sites $site)
	{
		$this->site = $site;
	}

	public function getSite()
	{
		if (!$this->site) {
			throw new Exception('site not set');
		}

		return $this->site;
	}

	public function setPasswordStrategy($strategy)
	{
		$this->passwordStrategy = $strategy;
	}

	protected function clearVisitors()
	{
		Logger::info('visitors', 'removing site visitors', [
			'site_id' => $this->getSite()->id
		]);
		array_map(function($visitor) {
			$this->repository()->destroy($visitor);
		}, $this->repository()->findBySiteId($this->getSite()->id));
	}

	protected function getVisitor($data)
	{
		$visitor = null;

		if (self::EXCLUSIVE != $this->method) {
			$visitor = $this->repository()->findByEmailAndSite($data['email'], $this->getSite()->id);
		}

		if ($visitor) {
			$visitor->setAttributes($data);
		} else {
			$visitor = $this->buildVisitor($data);
		}

		return $visitor;
	}

	function updateVisitor($visitor, $passwordGenerationService, $resend)
	{
		Logger::info('visitors', 'updating visitor', ['email' => $visitor->email()]);

		if ($resend && !$visitor->lastLogin()) {
			$password = $passwordGenerationService->generate($visitor, $this->passwordStrategy, $this->getSite());
			$this->sendVisitorEmail([
				'email' => $visitor->email(),
				'password' => $password,
			]);
		} else if ($visitor->lastLogin()) {
			Logger::info('visitors', 'invite not sent because visitor already logged in', [
				'email' => $visitor->email()
			]);
		}

		$this->repository()->update($visitor);
	}

	function createVisitor($visitor, $passwordGenerationService)
	{
		$password = $passwordGenerationService->generate($visitor, $this->passwordStrategy, $this->getSite());
		Logger::info('visitors', 'creating visitor', [
			'email' => $visitor->email(),
			'password' => $password,
		]);
		$this->repository()->create($visitor);
		$this->sendVisitorEmail([
			'email' => $visitor->email(),
			'password' => $password,
		]);
	}

	protected function buildVisitor($data)
	{
		$data['site_id'] = $this->getSite()->id;

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
		I18n::locale($this->getSite()->language);
		$segment = MeuMobi::currentSegment();
		$data['title'] = s('[%s]: Get started', $this->getSite()->title);
		$data['segment'] = $segment;
		$data['site'] = $this->getSite();
		$mailer = new Mailer([
			'from' => $segment->email,
			'to' => $data['email'],
			'subject' => $data['title'],
			'views' => ['text/html' => 'visitors/password_mail.htm'],
			'layout' => 'mail',
			'data' =>  $data,
		]);

		Logger::info('visitors', 'sending visitor invite email', [
			'email' => $data['email'],
			'password' => $data['password'],
		]);

		return $mailer->send();
	}
}
