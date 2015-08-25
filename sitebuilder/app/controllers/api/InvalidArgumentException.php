<?php

namespace app\controllers\api;

class InvalidArgumentException extends \InvalidArgumentException
{
	public $status = 400;
	protected $message = 'Bad Request';
}
