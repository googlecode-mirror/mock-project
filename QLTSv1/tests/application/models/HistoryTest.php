<?php

require_once 'Zend\Test\PHPUnit\ControllerTestCase.php';
require_once 'application\modules\asset\models\DbTable\History.php';
require_once 'application\modules\asset\models\DbTable\Item.php';
require_once 'Zend\Config\Ini.php';
require_once 'Zend\Db\Table.php';
/**
 * test case.
 */
class HistoryTest extends Zend_Test_PHPUnit_ControllerTestCase{
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected $history;
	protected function setUp() {
		parent::setUp ();
		
	// TODO Auto-generated HistoryTest::setUp()
	

	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated HistoryTest::tearDown()
		

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
 		$this->history = new Asset_Model_DbTable_History();
 		$this->item = new Asset_Model_DbTable_Item();
 		$this->history->delete("1=1");
 		$this->item->delete("1=1");
	}
	
	//Test viec them History
	public function testAddHistory()
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
		$this->item->addItem($ma,$Ten,$Description,$Type,$StartDate,$Price,$Time,$Status,$Place);
		$this->assertEquals($this->history->addHistory(1,2,$this->item->checkMaTS('12345'),"Muon","2011-7-20"),1);
	}

	//Test viec select History
	public function testSelectHistory()
	{
		$result = $this->history->getHistoryFromOthers(1,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->assertEquals($r['RUserID'],2);
		}	
	}
	
	//Test viec edit History
	public function testEditHistory()
	{
		$result = $this->history->getHistoryFromOthers(1,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->history->setDetail($r['HistoryID'],'A');
		}
		$result = $this->history->getHistoryFromOthers(1,null,null,null,null);
		foreach($result as $r)
		{
			$this->assertEquals($r['Detail'],'A');
		}
	}
	
	//Test viec xoa History
	public function testDeleteHistory()
	{
		$result = $this->history->getHistoryFromOthers(1,null,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->history->deleteHistory($r['HistoryID']);
		}
		$this->assertEquals($this->history->getHistoryFromOthers(1,null,null,null,null),null);
	}
}

