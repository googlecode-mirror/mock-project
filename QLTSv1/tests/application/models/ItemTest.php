<?php

require_once 'Zend\Test\PHPUnit\ControllerTestCase.php';
require_once 'application\modules\asset\models\DbTable\Item.php';
require_once 'Zend\Config\Ini.php';
require_once 'Zend\Db\Table.php';
/**
 * test case.
 */
class ItemTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected $item;
	protected function setUp() {
		parent::setUp ();
	
	// TODO Auto-generated ItemTest::setUp()
	

	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated ItemTest::tearDown()
		

		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
	//	$this->testCanAddItem();
		// TODO Auto-generated constructor
	 
		if(!defined('ROOT_DIR')) {                                        
    		define('ROOT_DIR', 
        dirname(dirname(dirname(__FILE__))));} 
	    if(Zend_Registry::isRegistered('db')) {       
            $this->db = Zend_Registry::get('db');      
        } else {
                 		$dbConfig =  array( 'host'     => 'localhost',
    										'username' => 'root',
    						    			'password' => 'fischer',
    										'dbname'   => 'qlts_test');       
            $db = Zend_Db::factory('Pdo_Mysql',$dbConfig);                                              
            $this->db = $db;                   
            Zend_Registry::set('db', $db);                           
        }
        Zend_Db_Table::setDefaultAdapter($this->db);  	
 		$this->item = new Asset_Model_DbTable_Item();
 		$this->item->delete("1=1");
	}
	
	//Test truong hop add thanh cong
	public function testCanAddItem()
	{
		$ma ='12345';
		$Ten='Monitor';
		$Description='Man hinh 24 inch';
		$Type='0';
		$StartDate='2000-12-01';
		$Price ='300000';
		$Time='24';
		$Status='0';
		$Place='lab1';
		$this->assertEquals($this->item->addItem($ma,$Ten,$Description,$Type,$StartDate,$Price,$Time,$Status,$Place),1);
		
		$ma ='12346';
		$Ten='CPU';
		$Description='Pen IV';
		$Type='1';
		$StartDate='2000-12-01';
		$Price ='1000000';
		$Time='24';
		$Status='0';
		$Place='lab2';
		$this->assertEquals($this->item->addItem($ma,$Ten,$Description,$Type,$StartDate,$Price,$Time,$Status,$Place),1);
		
		$ma ='12347';
		$Ten='Keyboard';
		$Description='HP Keyboard';
		$Type='0';
		$StartDate='2000-12-01';
		$Price ='100000';
		$Time='24';
		$Status='0';
		$Place='lab1';
		$this->assertEquals($this->item->addItem($ma,$Ten,$Description,$Type,$StartDate,$Price,$Time,$Status,$Place),1);
	}
	
	//Test truong hop trung ma
	public function testAddMaExist()	
	{
		$ma ='12345';
		$Ten='Monitor';
		$Description='Man hinh 24 inch';
		$Type='0';
		$StartDate='2000-12-01';
		$Price ='300000';
		$Time='24';
		$Status='0';
		$Place='lab1';
		$this->assertEquals($this->item->addItem($ma,$Ten,$Description,$Type,$StartDate,$Price,$Time,$Status,$Place),-1);
	}	
	
	//Test truong hop gia tri Null
	public function testAddNullValues()
	{		
		$ma ='123456';
		$Ten='Monitor';
		$Description='Man hinh 24 inch';
		$Type='0';
		$StartDate='2000-12-01';
		$Price =NUll;
		$Time='24';
		$Status='0';
		$Place='lab1';
		$this->assertEquals($this->item->addItem($ma,$Ten,$Description,$Type,$StartDate,$Price,$Time,$Status,$Place),0);
	}
	
	//Test select
	public function testSelectItem()
	{
		$result=$this->item->getItemfromOthers(null,'0',null,null,null,null,null,null,null,null);
		$this->assertNotEquals($result,null);
		$i=0;
		foreach($result as $r)
		{
			if($i==0) $this->assertEquals($r['Ma_tai_san'],'12345');
			else $this->assertEquals($r['Ma_tai_san'],'12347');
			$i++;
		}
		$this->assertEquals($this->item->getItemFromMa('123456'),NULL);
	}
	
	//Test truong hop edit gia tri
	public function testEditItem()
	{
		$result=$this->item->getItemFromMa('12346');
		$this->assertEquals($this->item->editItem($result['ItemID'],12345,$result['Ten_tai_san'],$result['Description'],
								$result['Type'],$result['StartDate'],$result['Price'],$result['WarrantyTime'],$result['Status'],$result['Place']),-1);
		$this->assertEquals($this->item->editItem($result['ItemID'],$result['Ma_tai_san'],$result['Ten_tai_san'],$result['Description'],
								$result['Type'],$result['StartDate'],3000000,$result['WarrantyTime'],$result['Status'],$result['Place']),1);
		$result=$this->item->getItemFromMa('12346');
		$this->assertEquals($result['Price'],3000000);								
	}
	
	//Test viec xoa item
	public function testDeleteItem()
	{
		$result=$this->item->getItemFromMa('12347');
		$this->item->deleteItem($result['ItemID']);
		$this->assertEquals($this->item->getItemFromMa('12347'),NULL);
	}
}
?>
