<?php
	class HistoryController extends Zend_Controller_Action{
		public function init(){
		
		}
		public function detailAction(){
			$startDate=$_POST['startDate'];
			$endDate=$_POST['endDate'];
			$itemID=$_POST['itemID'];
			$history = new Asset_Model_DbTable_History;
			$resultHistory=$history->getHistoryFromOthers(null,null,$itemID,$startDate,$endDate);
			
			return $resultHistory; 
/*	Ket qua tra ve la history cua item tu startDate den endDate		
 * $LusedID=$resultHistory[0];
 * $RusedID=$resultHistory[1];
*/
		}
		
	}
?>