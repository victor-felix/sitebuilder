<?php

use lithium\core\ErrorHandler;

ErrorHandler::apply(array('lithium\action\Dispatcher', 'run'),
    array('type' => 'app\models\sites\MissingSiteException'),
    function($exception, $params) {
        echo json_encode(array('error' => $exception['message']));
    }
);
