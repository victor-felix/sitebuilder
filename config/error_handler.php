<?php

set_error_handler(function($no, $str, $file, $line, $context) {
	file_put_contents(ERROR_LOG, $str, FILE_APPEND);
	throw new Exception($str);
}, -1);
