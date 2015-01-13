<?php

namespace app\controllers\api;

class PreflightController extends \app\controllers\api\ApiController {
	protected $beforeFilter = ['headers'];

	public function index() {
		$headers = [
			'Request-Method' => 'Allow-Method',
			'Request-Headers' => 'Allow-Headers',
		];

		foreach ($headers as $request => $response) {
			$env = $this->headerToEnv('Access-Control-' . $request);
			$req = $this->request->env($env);
			$this->response->headers('Access-Control-' . $response, $req);
		}
	}

	protected function headerToEnv($header) {
		return 'HTTP_' . strtoupper(str_replace('-', '_', $header));
	}
}
