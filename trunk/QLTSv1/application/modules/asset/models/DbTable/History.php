<?php
/* 
 * Classname: Asset_Model_DbTable_History
 *
 * Version 1.0
 *
 * Date: 27/06/2011
 */
    class Asset_Model_DbTable_History extends Zend_Db_Table_Abstract{
        
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
            $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Xoa History theo ID
        public function deleteHistory($id){
            $this->delete('HistoryID = '. $this->_db->quote($id, 'INTEGER'));
        }

        public function setLUserID($id, $value){
            $data = array('LUserID'=>$value);
            $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setRUserID($id, $value){
            $data = array('RUserID'=>$value);
            $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setItemID($id, $value){
            $data = array('ItemID'=>$value);
            $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setDetail($id, $value){
            $data = array('Detail'=>$LUserID);
            $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setDate($id, $value){
            $data = array('Date'=>$value);
            $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Lay mot History ra theo ID
        public function getHistoryFromID($id){
            $row = $this->fetchRow('HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
            if(!$row){
                return null;
            }
            return $row->toArray();
        }
        
        //Lay cac History tu cac thong so khac
        public function getHistoryFromOthers($LUserID, $RUserID, $ItemID, $StartDate, $EndDate){
            $sql = '1=1';
            if($LUserID!=null){
                $sql = $sql. ' AND LUserID = ' . $this->_db->quote($LUserID, 'INTEGER');
            }
            if($RUserID!=null){
                $sql = $sql. ' AND RUserID = ' . $this->_db->quote($RUserID, 'INTEGER');
            }
            if($ItemID!=null){
                $sql = $sql. ' AND ItemID = ' . $this->_db->quote($ItemID, 'INTEGER');
            }
            if($StartDate!=null){
                $sql = $sql. ' AND Date >= ' . $StartDate;
            }
            if($EndDate!=null){
                $sql = $sql. ' AND Date <= ' . $EndDate;
            }
            $sql=$sql.';';
            $result = $this->fetchAll($this->select()->where($sql));
            if(!$result){
                return null;
            }
            return $result;
        }
    }
?>
