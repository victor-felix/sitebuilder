<?php
require_once 'config/settings.php';

class ResetCategoriesOrder {
    public static function migrate($connection) {
    	set_time_limit (0);
        $allSites = Model::load('Sites')->all();
        $category = Model::load('Categories');
        foreach ($allSites as $site) {
        	$category->resetOrder($site->id);
        }
    }
}
