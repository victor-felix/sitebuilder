<?php
use app\models\Items;

require dirname(__DIR__) . '/config/cli.php';

$category_id = $argv[1];
echo $category_id;
var_dump(Items::resetItemsOrdering($category_id));