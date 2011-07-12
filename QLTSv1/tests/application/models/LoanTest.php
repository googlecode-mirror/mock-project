<?php

require_once 'Zend\Test\PHPUnit\ControllerTestCase.php';
require_once 'application\modules\asset\models\DbTable\Loan.php';
require_once 'application\modules\asset\models\DbTable\Item.php';
require_once 'Zend\Config\Ini.php';
require_once 'Zend\Db\Table.php';
/**
 * test case.
 */
class LoanTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected $loan;
	protected function setUp() {
		parent::setUp ();
		
	// TODO Auto-generated LoanTest::setUp()
	

	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated LoanTest::tearDown()
		

		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
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
 		$this->loan= new Asset_Model_DbTable_Loan();
 		$this->item = new Asset_Model_DbTable_Item();
 		$this->loan->delete("1=1");
 		$this->item->delete("1=1");
	}
	
	//Test add
	public function testAddLoan()
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
		$this->assertEquals($this->loan->addLoan('12345',2,"Muon","2011-7-20"),1);
	}

	//Test select
	public function testSelectLoan()
	{
		$result = $this->loan->getLoanFromMa('12345');
		$this->assertNotEquals($result,null);
		$this->assertEquals($result['UserID'],2);
	}
	
	//Test edit
	public function testEditLoan()
	{
		$result = $this->loan->getLoanFromOthers(2,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->loan->editLoan('12345',$r['UserID'],'A',$r['Date']);
		}
		$result = $this->loan->getLoanFromOthers(2,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->assertEquals($r['Detail'],'A');
		}
		
	}
	
	//Test delete
	public function testDeleteUpgrade()
	{
		$result = $this->loan->getLoanFromOthers(2,null,null,null);
		$this->assertNotEquals($result,null);
		foreach($result as $r)
		{
			$this->loan->deleteLoan('12345');
		}
		$this->assertEquals($this->loan->getLoanFromOthers(2,null,null,null),null);
	}

}