<?php
/*;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;       Item
;
;       @package Modules/asset/models
;       @version 1.0
;       @author TuanNA18
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
 * 
 */
	 require_once 'Zend/Db/Table/Abstract.php'; 
     class Asset_Model_DbTable_Item extends Zend_Db_Table_Abstract
    {
        protected $_name = 'iteminfor';     //Ten bang thao tac

        //Them tai san moi voi cac thuoc tinh cua no
        public function addItem($Ma,$Ten,$Description,$Type,$StartDate,$Price,$WarrantyTime,$Status,$Place){
             $data = array(
                    'Ma_tai_san'=>$Ma,
                    'Ten_tai_san'=>$Ten,
                    'Description'=>$Description,
                    'Type'=>$Type,
                    'StartDate'=>$StartDate,
                    'Price'=>$Price,
                    'WarrantyTime'=>$WarrantyTime,
                    'Status'=>$Status,
                    'Place'=>$Place
                    );
             if ($this->insert($data)!=true)
             {
                 return 0;                      //Thông tin nhập không hợp lệ
             }
             return 1;
        }

        //Lay thong tin tai san theo ID
        public function getItemFromID($id){
            $id=(int)$id;
            $row = $this->fetchRow('ItemID = ' . $this->_db->quote($id, 'INTEGER'));
            if (!$row) {
               return null;
            }
            return $row->toArray();
        }

        //Xoa mot tai san theo ID cua no
        public function deleteItem($id){
             $id = (int) $id;
             $arr=$this->getItemFromID($id);
             if ($arr['Status']==1)
             {
                 return 0;                  //Lỗi item đang được cho mượn, không xóa được
             }
             $this->delete('id = ' .(int)$id);
             return 1;
        }

        //Sua mot tai san voi cac thuoc tinh cua no
        public function editItem($id,$Ma,$Ten,$Description,$Type,$StartDate,$Price,$WarrantyTime,$Status,$Price){
             $data = array(
                    'Ma_tai_san'=>$Ma,
                    'Ten_tai_san'=>$Ten,
                    'Description'=>$Description,
                    'Type'=>Type,
                    'Start Date'=>$StartDate,
                    'Price'=>$Price,
                    'WarrantyTime'=>$WarrantyTime,
                    'Status'=>$Status,
                    'Place'=>$Place
                    );
             if ($this->update($data,'id = ' .(int)$id)!=true)
             {
                 return 0;                   //Thông tin sửa không hợp lệ
             }
             return 1;
        }

        //Set thuoc tinh Ma theo ID
        public function setMa($id,$value){
             $data=array('Ma_tai_san'=>$value);
             if ($this->insert($data)!=true)
             {
                 return 0;
             }
             return 1;
        }

        //Set thuoc tinh Ten theo id
        public function setName($id,$value){
             $data=array('Ten_tai_san'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Set thuoc tinh mo ta theo id
        public function setDescription($id,$value){
             $data=array('Description'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Set thuoc tinh kieu theo ID
        public function setType($id,$value){
             $data=array('Type'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Set thuoc tinh ngay bat dau mua theo ID
        public function setStartDate($id,$value){
             $data=array('Start Date'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Set thuoc tinh gia theo ID
        public function setPrice($id,$value){
             $data=array('Price'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Set thoi han bao hanh theo ID
        public function setWarrantyTime($id,$value){
             $data=array('WarrantyTime'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Set trang thai theo ID
        public function setStatus($id,$value){
             $data=array('Status'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        //Set vi tri theo ID
        public function setPlace($id,$value){
             $data=array('Place'=>$value);
             $this->update($data,'id = ' . $this->_db->quote($id, 'INTEGER'));
        }

        

        //Lay thong tin tai san theo Ma
        public function getItemFromMa($Ma){
             $row = $this->fetchRow("Ma_tai_san = '" . $this->_db->quote($Ma) . "'");
             if (!$row) {
               return null;
            }
            return $row->toArray();
        }

        //Lay thong tin tai san tu cac thong tin khac
         public function getItemFromOthers($Ten, $Type, $Status, $Place, $StartPrice, $EndPrice, $StartTime, $EndTime,  $StartDate, $EndDate){
            $sql = '1=1';
            if ($Ten!=null)
            {
               $sql = $sql. ' AND Ten_tai_san LIKE %'.$Ten.'%';
            }
            if ($Type!=null)
            {
                $sql = $sql. ' AND Type = ' . $Type;
            }
            if ($Status!=null)
            {
                $sql = $sql. ' AND Status = ' . $Status;
            }
            if ($Place!=null)
            {
                $sql = $sql. ' AND Place LIKE %'.$Place.'%';
            }
            if ($StartPrice!=null)
            {
                $sql = $sql. ' AND Price >= ' . $this->_db->quote($StartPrice, 'INTEGER');
            }
            if ($EndPrice!=null)
            {
                $sql = $sql. ' AND Price <= ' . $this->_db->quote($EndPrice, 'INTEGER');
            }
            if ($StartTime!=null)
            {
                $sql = $sql. ' AND WarrantyTime >= ' . $this->_db->quote($StartTime, 'INTEGER');
            }
            if ($EndTime!=null)
            {
                $sql = $sql. ' AND WarrantyTime <= ' . $this->_db->quote($EndTime, 'INTEGER');
            }
            if ($StartDate!=null)
            {
                $sql = $sql. ' AND StartDate >= ' . $StartDate;
            }
            if ($EndDate!=null)
            {
                $sql = $sql. ' AND StartDate <= ' . $EndDate;
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
