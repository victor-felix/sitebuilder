<?php

class StringHelper extends Helper {
	public function truncate($string, $length=10, $trailing='...')
	{
		if (strlen($string) > $length) {
			$string = substr($string, 0, $length) . $trailing;
		}
		return $string;
	}
}