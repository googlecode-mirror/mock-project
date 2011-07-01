<?php
class RequestController extends Zend_Controller_Action{
	public function init(){
		
	}
	public function addAction(){ //user gui di
		$userID=$_COOKIE['ownID'];
		$maTaiSan=$_POST['maTaiSan'];
		$type=$_POST['type']; //Neu la 1 thi nang cap, neu la 0 thi la muon
		$detail=$_POST['detail'];
		$curtime=date("y-m-d");
		$accept=0;  //Gui request chua accept
		
		$request=new Asset_Model_DbTable_Request();
		$request->addRequest($userID,$maTaiSan,$type,$detail,$curtime,$accept);
	}
	public function acceptAction($id){
		$request=new Asset_Model_DbTable_Request();
		$request->setAccept($id,1); //IT manager dong y
		
	}
}

?>