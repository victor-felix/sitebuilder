<?php
//Handle preflight requests
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Accept, X-Authentication-Token, Content-Type');
    header("Content-Length: 0");
    header("Content-Type: text/plain");
		http_response_code(200);
		exit;
} 
