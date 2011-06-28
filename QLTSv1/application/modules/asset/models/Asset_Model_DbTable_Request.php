<?php
/*
 * Classname: Asset_Model_DbTable_Request
 *
 * Version 1.0
 *
 * Date: 27/06/2011
 */
    class Asset_Model_DbTable_Request extends Zend_Db_Table_Abstract
    {
        protected $_name='requestinfor';       //Ten bang thao tac

        //Them mot Request voi cac thuoc tinh di kem
        public function addRequest($UserID, $ItemID, $Type, $Detail, $Date, $Accept) {
            $data = array(
                    'UserID'=>$UserID,
                    'ItemID'=>$ItemID,
                    'Type'=>$Type,
                    'Detail'=>$Detail,
                    'Date'=>$Date,
                    'Accept'=>$Accept
                    );
            $this->insert($data);
        }

        //Sua mot Request voi cac thuoc tinh di kem
        public function editHistory($id, $UserID, $ItemID, $Type, $Detail, $Date, $Accept) {
            $id = (int) $id;
            $data = array(
                    'UserID'=>$UserID,
                    'ItemID'=>$ItemID,
                    'Type'=>$Type,
                    'Detail'=>$Detail,
                    'Date'=>$Date,
                    'Accept'=>$Accept
                    );
            $this->update($data, 'RequestID = ' . $id);
        }

        //Xoa Request theo ID
        public function deleteUpgrade($id){
            $this->delete('RequestID = '. (int) $id);
        }

        public function setUserID($id, $value){
            $data = array('UserID'=>$value);
            $this->update($data, 'RequestID = ' . (int) $id);
        }

        public function setType($id, $value){
            $data = array('Type'=>$value);
            $this->update($data, 'RequestID = ' . (int) $id);
        }

        public function setDetail($id, $value){
            $data = array('Detail'=>$value);
            $this->update($data, 'RequestID = ' . (int) $id);
        }

        public function setDate($id, $value){
            $data = array('Date'=>$LUserID);
            $this->update($data, 'RequestID = ' . (int) $id);
        }

        public function setAccept($id, $value){
            $data = array('Accept'=>$value);
            $this->update($data, 'RequestID = ' . (int) $id);
        }

        //Lay mot Request tu ID
        public function getRequestFromID($id)
        {
            $id = (int) $id;
            $row = $this->fetchRow('RequestID = ' . $id);
            if(!$row){
                throw new Exception('Không tìm thấy lịch sử yêu cầu theo đầu vào');
            }
            return $row->toArray();
        }
    }
?>
