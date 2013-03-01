<?php

namespace app\models\items;

class ItemNotFoundException extends \Exception
{
	public $status = 404;
}
