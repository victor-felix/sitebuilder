<?php

namespace app\models\sites;

use \Exception;

class MissingSiteException extends Exception {
    protected $message = 'site was not found';
}
