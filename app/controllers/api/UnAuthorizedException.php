<?php

namespace app\controllers\api;

class UnAuthorizedException extends \Exception
{
	public $status = 403;
	protected $message = 'authentication required';
}
