<?php

class StringHelper extends Helper
{

	public function truncate($string, $length = 10, $trailing = '...')
	{
		if (strlen($string) > $length) {
			$string = substr($string, 0, $length) . $trailing;
		}
		return $string;
	}

	public function pad($string, $length, $pad)
	{
		if ($string) {
			return str_pad($string, $length, $pad);
		}
	}
}