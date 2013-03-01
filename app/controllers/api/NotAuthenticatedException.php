<?php

namespace app\controllers\api;

class NotAuthenticatedException extends \Exception
{
	public $status = 403;
}
