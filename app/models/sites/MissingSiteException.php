<?php

namespace app\models\sites;

class MissingSiteException extends \Exception {
    public $status = 404;
}
