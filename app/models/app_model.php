<?php

class AppModel extends Model {
    public function toJSON() {
        return $this->data;
    }
}