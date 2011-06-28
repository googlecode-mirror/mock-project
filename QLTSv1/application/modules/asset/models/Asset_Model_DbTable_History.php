<?php
/* 
 * Classname: Asset_Model_DbTable_History
 *
 * Version 1.0
 *
 * Date: 27/06/2011
 */
    class Asset_Model_DbTable_History extends Zend_Db_Table_Abstract
    {
        protected $_name='historyinfor';       //Ten bang thao tac

        //Them mot History voi cac thuoc tinh di kem
        public function addHistory($LUserID, $RUserID, $ItemID, $Detail, $Date) {
            $data = array(
                    'LUserID'=>$LUserID,
                    'RUserID'=>$RUserID,
                    'ItemID'=>$ItemID,
                    'Detail'=>$Detail,
                    'Date'=>$Date
                    );
            $this->insert($data);
        }

        //Sua mot History voi cac thuoc tinh di kem
        public function editHistory($id, $LUserID, $RUserID, $ItemID, $Detail, $Date) {
            $id = (int) $id;
            $data = array(
                    'LUserID'=>$LUserID,
                    'RUserID'=>$RUserID,
                    'ItemID'=>$ItemID,
                    'Detail'=>$Detail,
                    'Date'=>$Date
                    );
            $this->update($data, 'HistoryID = ' . $id);
        }

        //Xoa History theo ID
        public function deleteHistory($id){
            $this->delete('HistoryID = '. (int) $id);
        }

        public function setLUserID($id, $value){
            $data = array('LUserID'=>$value);
            $this->update($data, 'HistoryID = ' . (int) $id);
        }

        public function setRUserID($id, $value){
            $data = array('RUserID'=>$value);
            $this->update($data, 'HistoryID = ' . (int) $id);
        }

        public function setItemID($id, $value){
            $data = array('ItemID'=>$value);
            $this->update($data, 'HistoryID = ' . (int) $id);
        }

        public function setDetail($id, $value){
            $data = array('Detail'=>$LUserID);
            $this->update($data, 'HistoryID = ' . (int) $id);
        }

        public function setDate($id, $value){
            $data = array('Date'=>$value);
            $this->update($data, 'HistoryID = ' . (int) $id);
        }

        //Lay mot History ra theo ID
        public function getHistoryFromID($id)
        {
            $id = (int) $id;
            $row = $this->fetchRow('HistoryID = ' . $id);
            if(!$row){
                throw new Exception('Không tìm thấy lịch sử bàn giao theo yêu cầu');
            }
            return $row->toArray();
        }
    }
?>
