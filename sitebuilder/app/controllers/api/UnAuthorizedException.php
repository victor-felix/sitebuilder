<?php

namespace app\controllers\api;

class UnAuthorizedException extends \Exception
{
	public $status = 401;
	protected $message = 'authentication required';
}
