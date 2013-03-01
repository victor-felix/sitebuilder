<?php

namespace app\controllers\api;

class InvalidArgumentException extends \InvalidArgumentException
{
	public $status = 422;
}
