<?php
/*
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;       Upgrade
;
;       @package Modules/asset/models
;       @version 1.0
;       @author TuanNA18
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
 */
    class Asset_Model_DbTable_Upgrade extends Zend_Db_Table_Abstract{

        protected $_name='upgradeinfor';       //Ten bang thao tac

        //Them mot Upgrade voi cac thuoc tinh di kem
        public function addUpgrade($UserID, $ManagerID, $ItemID, $Detail, $Date) {
            $data = array(
                    'UserID'=>$UserID,
                    'ManagerID'=>$ManagerID,
                    'ItemID'=>$ItemID,
                    'Detail'=>$Detail,
                    'Date'=>$Date
                    );
            $this->insert($data);
        }
        
        //Sua mot Upgrade voi cac thuoc tinh di kem
        public function editUpgrade($id, $UserID, $ManagerID, $ItemID, $Detail, $Date) {
            $id = (int) $id;
            $data = array(
                    'UserID'=>$UserID,
                    'ManagerID'=>$ManagerID,
                    'ItemID'=>$ItemID,
                    'Detail'=>$Detail,
                    'Date'=>$Date
                    );
            $this->update($data, 'UpgradeID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Xoa Upgrade theo ID
        public function deleteHistory($id){
            $this->delete('UpgradeID = '. $this->_db->quote($id, 'INTEGER'));
        }

        public function setUserID($id, $value){
            $data = array('UserID'=>$value);
            $this->update($data, 'UpgradeID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setManagerID($id, $value){
            $data = array('ManagerID'=>$value);
            $this->update($data, 'UpgradeID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setItemID($id, $value){
            $data = array('ItemID'=>$value);
            $this->update($data, 'UpgradeID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setDetail($id, $value){
            $data = array('Detail'=>$value);
            $this->update($data, 'UpgradeID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setDate($id, $value){
            $data = array('Date'=>$value);
            $this->update($data, 'UpgradeID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Lay mot ban Upgrade theo ID
        public function getUpgradeFromID($id){
            $row = $this->fetchRow('UpgradeID = ' . $this->_db->quote($id, 'INTEGER'));
            if (!$row)
            {
                return null;
            }
            return $row->toArray();
        }

        //Lay cac ban Upgrade theo cac yeu to khac
        public function getUpgradeFromOthers($UserID, $ManagerID, $ItemID, $StartDate, $EndDate){
            $sql = '1=1';
            if ($UserID!=null)
            {
                $sql = $sql. ' AND UserID = ' . $this->_db->quote($UserID, 'INTEGER');
            }
            if ($ManagerID!=null)
            {
                $sql = $sql. ' AND ManagerID = ' . $this->_db->quote($ManagerID, 'INTEGER');
            }
            if ($ItemID!=null)
            {
                $sql = $sql. ' AND ItemID = ' . $this->_db->quote($ItemID, 'INTEGER');
            }
            if ($StartDate!=null)
            {
                $sql = $sql. ' AND Date >= ' . $StartDate;
            }
            if ($EndDate!=null)
            {
                $sql = $sql. ' AND Date <= ' . $EndDate;
            }
            $sql=$sql.';';
            $result = $this->fetchAll($this->select()->where($sql));
            if (!$result)
            {
                return null;
            }
            return $result;
        }
    }
?>
