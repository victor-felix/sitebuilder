<?php
class CategoriesTest extends PHPUnit_Framework_TestCase
{
	protected $category;
 	protected $id;
	
	public static function setUpBeforeClass()
	{
		chdir(__DIR__);
		require_once realpath('../../../../../') . '/config/bootstrap.php';
		require_once 'config/settings.php';
		require_once 'config/connections.php';
		Model::load('Categories');
	}
	
    protected function setUp()
    {
    	//config categorie object
        $this->category = new Categories();
        $this->id = 109;
    }
 	
    public function testLoad()
    {
    	$category = $this->category->firstById($this->id);
    	$this->assertEquals($this->id, $category->id);
    	return $category;
    }
    
    /**
     * @group reset
     */    
    public function testResetOrders() {
    	//$this->markTestSkipped('must be revisited.');
    	
    	$this->assertTrue((bool)$this->category->resetOrder(1));
    }
        
    /**
     * @group getters
     * @depends testLoad
     */
    public function testGetFirst($category) {
    	$first = $category->getFirst();
    	echo "the first is $first->id\n";
    	$this->assertTrue((bool)$first->id);
    }
    
    /**
     * @group getters
     * @depends testLoad
     */
    public function testGetLast($category) {
		$last = $category->getLast();
		echo "the last is $last->id\n";
		$this->assertTrue((bool)$last->id);
		return $last;
    }
    
    /**
     * @group remove
     * @depends testGetLast
     */
    public function testRemoveAndOrderUpdate($last) {
    	//$this->markTestSkipped('must be revisited.');
    	
    	$oldOrder = $last->order;
		$penultimo = $last->findByOrder($last->order - 1);
		
    	if ($penultimo) {
    		echo "penultimo id $penultimo->id\n";
    		$penultimo->delete($penultimo->id);
    		$newLast = $last->getLast();
    		$this->assertEquals($oldOrder -1, $newLast->order);
    	}
    }
    
    /**
     * @depends testGetLast
     * @group create
     */
    public function testSetNewOrder($last) {
    	//$this->markTestSkipped('must be revisited.');
    	
    	$data = $last->data();
    	$data['id'] = null;
    	unset($data['created'],$data['modified'], $data['updated'], $data['order']);
    	$this->category->data = $data;
    	$this->category->save();
    	$this->assertEquals($last->order +1, $this->category->order);
    	return $this->category->data();
    }
    
    /**
     * @depends testSetNewOrder
     * @group create
     */
    public function testSetNewOrderNoChildren($lastAdded) {
    	$this->markTestSkipped('must be revisited.');
    	
    	$data = $lastAdded;
    	$data['parent_id'] = $lastAdded['id'];
    	$data['id'] = null;
    	unset($data['created'],$data['modified'], $data['updated'], $data['order']);
    	$this->category->data = $data;
    	$this->category->save();
    	$this->assertEquals(1, $this->category->order);
    }
    
    /**
     * @depends testLoad
     * @group move
     */
    public function testMoveUp($category) {
    	$this->markTestSkipped('must be revisited.');
    	
    	$currentOrder = $category->order;
    	$this->assertEquals($currentOrder - 1, $category->moveUp());
    }
    
    /**
     * @depends testLoad
     * @group move
     */
    public function testMoveDow($category) {
    	//$this->markTestSkipped('must be revisited.');
    	
    	$currentOrder = $category->order;
    	$this->assertEquals($currentOrder + 1 , $category->moveDown());
    }
}