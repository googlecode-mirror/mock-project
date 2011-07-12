<?php

require_once 'Zend\Test\PHPUnit\ControllerTestCase.php';
require_once 'application\modules\asset\models\DbTable\Upgrade.php';
require_once 'application\modules\asset\models\DbTable\Item.php';
require_once 'Zend\Config\Ini.php';
require_once 'Zend\Db\Table.php';
/**
 * test case.
 */
class UpgradeTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected $upgrade;
	protected function setUp() {
		parent::setUp ();
		
	// TODO Auto-generated UpgradeTest::setUp()
	

	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated UpgradeTest::tearDown()
		

		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
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
 		$this->upgrade= new Asset_Model_DbTable_Upgrade();
 		$this->item = new Asset_Model_DbTable_Item();
 		$this->upgrade->delete("1=1");
 		$this->item->delete("1=1");
	}
	
	//Test add
	public function testAddUpgrade()
	{
		$ma ='12345';
		$Ten='CPU';
		$Description='1GRAM';
		$Type='0';
		$StartDate='2000-12-01';
		$Price ='300000';
		$Time='24';
		$Status='0';
		$Place='lab1';	
		$this->item->addItem($ma,$Ten,$Description,$Type,$StartDate,$Price,$Time,$Status,$Place);
		$this->assertEquals($this->upgrade->addUpgrade(2,3,$this->item->checkMaTS('12345'),"Nang cap RAM","2011-7-20"),1);
	}

	//Test select
	public function testSelectUpgrade()
	{
		$result = $this->upgrade->getUpgradeFromOthers(2,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->assertEquals($r['ManagerID'],3);
		}	
	}
	
	//Test edit
	public function testEditUpgrade()
	{
		$result = $this->upgrade->getUpgradeFromOthers(2,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->upgrade->setDetail($r['UpgradeID'],'A');
		}
		$result = $this->upgrade->getUpgradeFromOthers(2,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->assertEquals($r['Detail'],'A');
		}
		
	}
	
	//Test delete
	public function testDeleteUpgrade()
	{
		$result = $this->upgrade->getUpgradeFromOthers(2,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->upgrade->deleteUpgrade($r['UpgradeID']);
		}
		$this->assertEquals($this->upgrade->getUpgradeFromOthers(1,null,null,null,null),null);
	}
}

