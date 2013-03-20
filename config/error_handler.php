<?php

set_error_handler(function($no, $str, $file, $line, $context) {
	throw new Exception($str);
}, -1);
