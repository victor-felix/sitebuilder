<?php

namespace app\models;

class RecordNotFoundException extends \Exception
{
	public $status = 404;
}
