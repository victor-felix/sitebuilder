<?php
use app\models\items\Articles;

use app\models\Items;

class CategoriesTest extends PHPUnit_Framework_TestCase
{
	
	protected $item;
	protected $id = "506adf511bad67dd2f000000";
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
 	
    public function testLoad()
    {
    	//$this->markTestSkipped('must be revisited.');
    	return Items::find('first', array('conditions' => array(
    			'_id' => $this->id,
    	)));
    }
    
    public function testResetOrders() {
    	//$this->markTestSkipped('must be revisited.');
    	
    	$reseted = Items::resetItemsOrdering(5);
    	$this->assertTrue($reseted > 0);
    }
        
    /**
     * @depends testLoad
     */
    public function testGetFirst($item) {
    	$this->markTestSkipped('must be revisited.');
    	$first = $item->getFirst();
    	echo "first item $first->title\n";
    	$this->assertTrue((bool)$first->_id);
    }
    
    /**
     * @depends testLoad
     */
    public function testGetLast($item) {
    	//$this->markTestSkipped('must be revisited.');
    	$last = $item->getLast();
    	echo "last item $last->title\n";
    	$this->assertTrue((bool)$last->_id);
    	return $last;
    }
    
    /**
     * @depends testGetLast
     */
    public function testRemoveAndOrderUpdate($last) {
    	$this->markTestSkipped('must be revisited.');
    	
    	$item = $last->findByOrder($last->order - 2);
    	$removed = Items::remove(array('_id' => $item->_id));
    	$this->assertTrue((bool)$removed);
    }
    
    /**
     * @depends testGetLast
     */
    public function testSetNewOrder($last) {
    	$this->markTestSkipped('must be revisited.');
    	
    	$data = $last->to('array');
    	unset($data['_id'], $data['created'], $data['modified'], $data['order']);
    	$item = Articles::create($data);
    	$item->save();
    	$this->assertEquals($last->order +1, $item->order);
    }

    /**
     * @depends testLoad
     */
    public function testMoveUp($item) {
    	$this->markTestSkipped('must be revisited.');
    	
    	$currentOrder = $item->order;
    	$this->assertEquals($currentOrder - 1, $item->moveUp());
    }
    
    /**
     * @depends testLoad
     */
    public function testMoveDown($item) {
    	$this->markTestSkipped('must be revisited.');
    	
    	$currentOrder = $item->order;
    	$this->assertEquals($currentOrder + 1, $item->moveDown());
    }
}