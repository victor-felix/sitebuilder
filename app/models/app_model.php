<?php

class AppModel extends Model {
    protected $defaultScope = array(
        'recursion' => 0,
        'orm' => true
    );
}