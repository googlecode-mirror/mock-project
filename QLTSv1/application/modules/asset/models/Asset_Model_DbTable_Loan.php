<?php
/*
 * Classname: Asset_Model_DbTable_Loan
 *
 * Version 1.0
 *
 * 27/06/2011
 *
 * 
 */
    class Asset_Model_DbTable_Loan extends Zend_Db_Table_Abstract
    {
        protected $_name='loaninfor';       //Ten bang du lieu thao tac

        //Them mot Loan voi cac thuoc tinh cua no
        public function addLoan($ItemID,$UserID,$Detail,$Date){
            $data = array(
                    'ItemID'=>$ItemID,
                    'UserID'=>$UserID,
                    'Detail'=>$Detail,
                    'Date'=>$Date
                    );
            if($this->insert($data)!=true){
                throw new Exception("Tài sản này đã trong danh sách cho mượn");
            }
        }

        //Edit mot Loan voi cac thuoc tinh
        public function editLoan($ItemID,$UserID,$Detail,$Date){
            $data = array(
                    'UserID'=>$UserID,
                    'Detail'=>$Detail,
                    'Date'=>$Date,
                    );
            $this->update($data,'ItemID = ' .(int) $ItemID);
        }

        //Xoa mot Loan theo ID
        public function deleteLoan($ItemID){
            $this->delete('ItemID = ' .(int) $ItemID);
        }

        //Set UserID
        public function setUserID($ItemID,$value){
            $data=array('UserID'=>$value);
           $this->update($data,'ItemID= ' .(int) $ItemID);
        }

        public function setDetail($ItemID,$value){
            $data=array('Detail'=>$value);
            $this->update($data,'ItemID= ' .(int) $ItemID);
        }

        public function setDate($ItemID,$value){
            $data = array('Date'=>$value);
            $this->update($data,'ItemID= '.(int)$ItemID);
        }

        //Get du lieu theo ItemID
        public function getFromItemID($ItemID){
            $ItemID = (int) $ItemID;
            $row=$this->fetchRow('ItemID = '. $ItemID);
            if(!$row) {
                throw new Exception("Không tìm thấy tài sản đang được mượn theo yêu cầu");
            }
            return $row->toArray();
        }
        
        //Get du lieu theo UserID
        public function getFromUserID($UserID) {
            $UserID = (int) $UserID;
            $result=$this->fetchAll('UserID = '. $UserID);
            if(!$result) {
                throw new Exception("Không tìm thấy tài sản đang nào được user mượn");
            }
            return $result;
        }
    }
?>
