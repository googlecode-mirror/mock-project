<?php

class ItemController extends Zend_Controller_Action{
	public function init(){
		
	}
/*	public function indexAction() {
		
	}*/
	public function addAction(){
		$maTaiSan=$_POST['maTaiSan'];
		$description=$_POST['description'];
		$type=$_POST['type'];
		$startDate=$_POST['startDate'];
		$startDateConverted = date("m/d/Y", strtotime($startDate));
		$price=$_POST['price'];
		$warrantyTime=$_POST['warrantyTime'];
		$status=$_POST['status'];
		$place=$_POST['place'];
		$item = new Asset_Model_DbTable_Item();
		$item->addItem($maTaiSan,$description,$type,$startDateConverted,$price,$warrantyTime,$status,$place);
	}
	public function editAction($maTaiSan){
		$maTaiSan=$_POST['maTaiSan'];
		$description=$_POST['description'];
		$type=$_POST['type'];
		$startDate=$_POST['startDate'];
		$startDateConverted = date("m/d/Y", strtotime($startDate));
		$price=$_POST['price'];
		$warrantyTime=$_POST['warrantyTime'];
		$status=$_POST['status'];
		$place=$_POST['place'];
		
		$item = new Asset_Model_DbTable_Item();
		$item->editItem($maTaiSan,$description,$type,$startDateConverted,$price,$warrantyTime,$status,$place);
	}
	public function deleteAction($maTaiSan){
		$item = new Asset_Model_DbTable_Item();
		$item->delete($maTaiSan);
	}
	public function detailAction($maTaiSan) {
		$item = new Asset_Model_DbTable_Item();
		$array=$item->getItemFromMa($maTaiSan);
		return $array;
/*		$array la mang chua thong tin detail cua tai san, hien thi chi tiet nhu sau:
		$maTaiSan=$array[0];
		$description=$array[1];
		$type=$array[2];
		$startDate=$array[3];
		$price=$array[4];
		$warrantyTime=$array[5];
		$status=$array[6];
		$place=$array[7];
*/
	}
}
?>
