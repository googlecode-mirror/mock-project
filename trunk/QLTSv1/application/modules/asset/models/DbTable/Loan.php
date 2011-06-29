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
        public function addLoan($Ma,$UserID,$Detail,$Date){
            $data = array(
                    'Ma_tai_san'=>$Ma,
                    'UserID'=>$UserID,
                    'Detail'=>$Detail,
                    'Date'=>$Date
                    );
            if($this->insert($data)!=true){
                return 0;                           //Dữ liệu nhập không hợp lệ
            }
            return 1;
        }

        //Edit mot Loan voi cac thuoc tinh
        public function editLoan($Ma,$UserID,$Detail,$Date){
            $data = array(
                    'UserID'=>$UserID,
                    'Detail'=>$Detail,
                    'Date'=>$Date,
                    );
            if($this->update($data,'Ma_tai_san = ' . $this->_db->quote($Ma))!=true){
                return 0;                           //Dữ liệu sửa không hợp lệ
            }
            return 1;
        }

        //Xoa mot Loan theo ID
        public function deleteLoan($Ma){
            $this->delete('Ma_tai_san = ' . $this->_db->quote($Ma));
        }

        //Set UserID
        public function setUserID($Ma,$value){
            $data=array('UserID'=>$value);
            $this->update($data,'Ma_tai_san = ' . $this->_db->quote($Ma));
        }

        public function setDetail($Ma,$value){
            $data=array('Detail'=>$value);
            $this->update($data,'Ma_tai_san = ' . $this->_db->quote($Ma));
        }

        public function setDate($Ma,$value){
            $data = array('Date'=>$value);
            $this->update($data,'Ma_tai_san = ' . $this->_db->quote($Ma));
        }

        //Get du lieu theo ItemID
        public function getLoanFromMa($Ma){
            $this->update($data,'Ma_tai_san = ' . $this->_db->quote($Ma));
            if(!$row) {
                throw new Exception("Không tìm thấy tài sản đang được mượn theo yêu cầu");
            }
            return $row->toArray();
        }
        
        //Get du lieu theo cac yeu to khac
        public function getLoanFromOthers($UserID, $StartDate, $EndDate) {
            $sql = '1=1';
            if($UserID!=null){
                $sql = $sql. ' AND UserID = ' . $this->_db->quote($UserID, 'INTEGER');
            }
            if($StartDate!=null){
                $sql = $sql. ' AND Date >= ' . $StartDate;
            }
            if($EndDate!=null){
                $sql = $sql. ' AND Date <= ' . $EndDate;
            }
            $sql=$sql.';';
            $result = $this->fetchAll($this->select()->where(sql));
            if(!$result){
                return null;
            }
            return $result;
        }
        public function getLoanFromUsername($Name, $StartDate, $EndDate){
            $sql = '1=1';
            if($UserID!=null){
                $sql = $sql. ' AND m.Username LIKE %'.$Name.'%';
            }
            if($StartDate!=null){
                $sql = $sql. ' AND loaninfor.Date >= ' . $StartDate;
            }
            if($EndDate!=null){
                $sql = $sql. ' AND loaninfor.Date <= ' . $EndDate;
            }
            $sql=$sql.';';
            $result = $this->fetchAll($this->select()->join('memberinfor as m','m.UserID = loaninfor.UserID')
                                                     ->where($sql));
            if(!$result){
                return null;
            }
            return $result;
        }
    }
?>
