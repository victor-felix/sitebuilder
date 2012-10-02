<?php
use app\models\Items;

require_once 'config/settings.php';

class ResetItemsOrders {
    public static function migrate($connection) {
    	set_time_limit (0);
        $categories = Model::load('Categories')->toList();
        foreach ($categories as $id => $title) {
        	Items::resetItemsOrdering($id);
        }
    }
}
