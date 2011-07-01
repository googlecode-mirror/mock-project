<?php
class LoanController extends Zend_Controller_Action{
	public function init(){
		
	}
	public function addAction(){
		$ItemID=$_POST['itemID'];
		$UserID=$_POST['userID'];
		$Detail=$_POST['detail'];
		$loan=new Asset_Model_DbTable_Loan();
		$curtime=date("y-m-d");
		$loan->addLoan($ItemID,$UserID,$Detail,$curtime);
	}
	public function deleteAction($ItemID){
		$loan=new Asset_Model_DbTable_Loan();
		$loan->deleteLoan($ItemID);
	}
	
}
?>
