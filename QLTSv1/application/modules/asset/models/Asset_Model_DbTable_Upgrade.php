<?php
/*
 * Classname: Asset_Model_DbTable_Upgrade
 *
 * Version 1.0
 *
 * Date: 27/06/2011
 */
    class Asset_Model_DbTable_Upgrade extends Zend_Db_Table_Abstract
    {
        protected $_name='upgradeinfor';       //Ten bang thao tac

        //Them mot Upgrade voi cac thuoc tinh di kem
        public function addUpgrade($UserID, $ManagerID, $ItemID, $Detail, $Date) {
            $data = array(
                    'UserID'=>$LUserID,
                    'ManagerID'=>$RUserID,
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
            $this->update($data, 'UpgradeID = ' . $id);
        }

        //Xoa Upgrade theo ID
        public function deleteHistory($id){
            $this->delete('UpgradeID = '. (int) $id);
        }

        public function setUserID($id, $value){
            $data = array('UserID'=>$value);
            $this->update($data, 'UpgradeID = ' . (int) $id);
        }

        public function setManagerID($id, $value){
            $data = array('ManagerID'=>$value);
            $this->update($data, 'UpgradeID = ' . (int) $id);
        }

        public function setItemID($id, $value){
            $data = array('ItemID'=>$value);
            $this->update($data, 'UpgradeID = ' . (int) $id);
        }

        public function setDetail($id, $value){
            $data = array('Detail'=>$LUserID);
            $this->update($data, 'UpgradeID = ' . (int) $id);
        }

        public function setDate($id, $value){
            $data = array('Date'=>$value);
            $this->update($data, 'UpgradeID = ' . (int) $id);
        }

        //Lay mot ban Upgrade theo ID
        public function getUpgradeFromID($id)
        {
            $id = (int) $id;
            $row = $this->fetchRow('UpgradeID = ' . $id);
            if(!$row){
                throw new Exception('Không tìm thấy lịch sử nâng cấp theo yêu cầu');
            }
            return $row->toArray();
        }
    }
?>
