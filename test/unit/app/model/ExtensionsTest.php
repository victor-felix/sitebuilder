<?php
use app\models\items\Articles;

use app\models\Items;

class ExtensionsTest extends PHPUnit_Framework_TestCase
{
	public static function setUpBeforeClass()
	{
		chdir(__DIR__);
		require_once realpath('../../../../../') . '/config/bootstrap.php';
		require_once 'config/settings.php';
		require_once 'config/connections.php';
	}
	
    protected function setUp()
    {
    	
    }
 	
    public function testCreate() {
    	
    }
    
    /**
     * depends testLoad
     *
    public function testGetLast($item) {
    	//$this->markTestSkipped('must be revisited.');
    	$last = $item->getLast();
    	echo "last item $last->title\n";
    	$this->assertTrue((bool)$last->_id);
    	return $last;
    }*/
    
}