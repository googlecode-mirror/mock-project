<?php
/*
 * Classname: Asset_Model_DbTable_Request
 *
 * Version 1.0
 *
 * Date: 27/06/2011
 */
    class Asset_Model_DbTable_Request extends Zend_Db_Table_Abstract{
        protected $_name='requestinfor';       //Ten bang thao tac

        //Them mot Request voi cac thuoc tinh di kem
        public function addRequest($UserID, $Ma, $Type, $Detail, $Date, $Accept) {
            $data = array(
                    'UserID'=>$UserID,
                    'Ma_tai_san'=>$Ma,
                    'Type'=>$Type,
                    'Detail'=>$Detail,
                    'Date'=>$Date,
                    'Accept'=>$Accept
                    );
            if($this->insert($data)!=true){
               return 0;                        //Thông tin yêu cầu nhập không hợp lệ
            }
            return 1;
        }

        //Sua mot Request voi cac thuoc tinh di kem
        public function editRequest($id, $UserID, $Ma, $Type, $Detail, $Date, $Accept) {
            $id = (int) $id;
            $data = array(
                    'UserID'=>$UserID,
                    'Ma_tai_san'=>$Ma,
                    'Type'=>$Type,
                    'Detail'=>$Detail,
                    'Date'=>$Date,
                    'Accept'=>$Accept
                    );
            if($this->update($data, 'RequestID = ' . $id)!=true){
                return 0;                       //Thông tin yêu cầu sửa không hợp lệ
            }
            return 1;
        }

        //Xoa Request theo ID
        public function deleteRequest($id){
            $this->delete('RequestID = '. $this->_db->quote($id, 'INTEGER'));
        }

        public function setUserID($id, $value){
            $data = array('UserID'=>$value);
            $this->update($data, 'RequestID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setType($id, $value){
            $data = array('Type'=>$value);
            $this->update($data, 'RequestID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setDetail($id, $value){
            $data = array('Detail'=>$value);
            $this->update($data, 'RequestID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setDate($id, $value){
            $data = array('Date'=>$LUserID);
            $this->update($data, 'RequestID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        public function setAccept($id, $value){
            $data = array('Accept'=>$value);
            $this->update($data, 'RequestID = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Lay mot Request tu ID
        public function getRequestFromID($id){
            $row = $this->fetchRow('RequestID = ' . $this->_db->quote($id, 'INTEGER'));
            if(!$row){
                return null;
            }
            return $row->toArray();
        }

        //Lay cac Request theo yeu cau
         public function getRequestFromOthers($UserID, $Ma, $Type, $Accept, $StartDate, $EndDate){
            $sql = '1=1';
           if($UserID!=null){
               $sql = $sql. ' AND UserID = ' . $this->_db->quote($UserID, 'INTEGER');
            }
            if($Ma!=null){
                $sql = $sql. ' AND Ma_tai_san = ' . $this->_db->quote($Ma);
            }
            if($Type!=null){
                $sql = $sql. ' AND Type = ' . $Type;
            }
            if($Accept!=null){
                $sql = $sql. ' AND Accept = ' . $Accept;
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