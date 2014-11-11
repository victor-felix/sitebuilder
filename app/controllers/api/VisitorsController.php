<?php

namespace app\controllers\api;

use \lithium\storage\Session;

class VisitorsController extends ApiController
{
	public function login() {
		//Set the stub visitor cookie
		$visitor = $this->request->data; 
		Session::write(
			\Auth::SESSION_KEY,
			serialize($visitor) 
		);
		return;
		//Implemetation when model visitor exists
		/*$visitor = Visitor::where(email: $request->params->email); //load visitor from database
		if ($visitor && $visitor->authenticate(password: $request->params->password)) {
			$visitor->addDevice($request->params->device_id, $request->params->model);// add device to visitor if needed
			$response->cokkie = $visitor; //add authentication cokkie, 
			return; //return empty success(200) response
		}
		throw new UnAuthorizedException('Invalid visitor');// raises Exception if the credentials are invalid
		*/
	}
}
