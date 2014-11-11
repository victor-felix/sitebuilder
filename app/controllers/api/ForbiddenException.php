<?php

namespace app\controllers\api;

class ForbiddenException extends \Exception
{
	public $status = 403;
	protected $message = 'Forbidden Access';
}
