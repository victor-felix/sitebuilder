<?php

\lithium\storage\Session::config(array(
		'cookie' => array('adapter' => 'Cookie', 'expire' => '+30 day'),
    'default' => array(
        'adapter' => 'Php'
    )
));
