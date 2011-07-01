<?php
class UpgradeController extends Zend_Controller_Action{
	public function init(){
		
	}
	public function addAction(){
		$ownID=$_COOKIE['ownID']; //read from Cookie of admin when login
		$userID=$_POST['userID'];
		$itemID=$_POST['itemID'];
		$detail=$_POST['detail'];
		$curtime=date("y-m-d");
		$upgrade=new Asset_Model_DbTable_Upgrade();
		$upgrade->addUpgrade($userID,$ownID,$itemID,$detail,$curtime);
	}
	public function deleteAction($id){
		$upgrade=new Asset_Model_DbTable_Upgrade();
		$upgrade->deleteUpgrade($id);
	}
	public function listAction(){
		$itemID=$_POST['itemID'];
		$startDate=$_POST['startDate'];
		$endDate=$_POST['endDate'];
		$upgrade=new Asset_Model_DbTable_Upgrade();
		$upgradeList=$upgrade->getUpgradeFromOthers(null,null,$itemID,$startDate,$endDate);
		
		return $upgrade;  //list nhung upgrade theo itemID tinh tu startDate cho den endDate
	}
	
}
?>