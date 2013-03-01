<?php

namespace app\models\sites;

class MissingSiteException extends \Exception {
    protected $status = 404;
}
