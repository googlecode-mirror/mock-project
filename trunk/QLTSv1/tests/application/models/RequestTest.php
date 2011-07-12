<?php

require_once 'Zend\Test\PHPUnit\ControllerTestCase.php';
require_once 'application\modules\asset\models\DbTable\Request.php';
require_once 'application\modules\asset\models\DbTable\Item.php';
require_once 'Zend\Config\Ini.php';
require_once 'Zend\Db\Table.php';
/**
 * test case.
 */
class RequestTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected $request;
	protected function setUp() {
		parent::setUp ();
		
	// TODO Auto-generated RequestTest::setUp()
	

	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated RequestTest::tearDown()
		

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
 		$this->request= new Asset_Model_DbTable_Request();
 		$this->item = new Asset_Model_DbTable_Item();
 		$this->request->delete("1=1");
 		$this->item->delete("1=1");
	}
	
	//Test add
	public function testAddRequest()
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
		$this->assertEquals($this->request->addRequest(2,'12345',0,"Muon","2011-7-20",0),1);
	}

	//Test select
	public function testSelectRequest()
	{
		$result = $this->request->getRequestFromOthers(null,'12345',null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->assertEquals($r['Type'],'0');
			$this->assertEquals($r['Accept'],'0');
		}	
	}
	
	//Test edit
	public function testEditRequest()
	{
		$result = $this->request->getRequestFromOthers(2,null,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->request->setDetail($r['RequestID'],'A');
		}
		$result = $this->request->getRequestFromOthers(2,null,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->assertEquals($r['Detail'],'A');
		}
		
	}
	
	//Test delete
	public function testDeleteUpgrade()
	{
		$result = $this->request->getRequestFromOthers(2,null,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->request->deleteRequest($r['RequestID']);
		}
		$this->assertEquals($this->request->getRequestFromOthers(2,null,null,null,null,null),null);
	}
}

