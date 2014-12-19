<?php

namespace meumobi\sitebuilder\repositories;

class RecordNotFoundException extends Exception
{
	public $status = 404;
	protected $message = 'Not found';
}
