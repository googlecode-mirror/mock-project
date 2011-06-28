<?php
/* Classname: Asset_Model_DbTable_Item
 *
 * Version 1.0
 *
 * 27/06/2011
 * 
 */
     class Asset_Model_DbTable_Item extends Zend_Db_Table_Abstract
    {
        protected $_name = 'iteminfor';     //Ten bang thao tac

        //Them tai san moi voi cac thuoc tinh cua no
        public function addItem($Ma,$Ten,$Description,$Type,$StartDate,$Price,$WarrantyTime,$Status,$Price){
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
             if($this->insert($data)!=true){
                 throw new Exception("Mã tài sản trùng lặp");
             }
        }

        //Xoa mot tai san theo ID cua no
        public function deleteItem($id){
             $this->delete('id = ' .(int)$id);
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
             if($this->update($data,'id = ' .(int)$id)!=true) throw new Exception("Mã tài sản trùng lặp");
        }

        //Set thuoc tinh Ma theo ID
        public function setMa($id,$value){
             $data=array('Ma_tai_san'=>$value);
             if($this->insert($data)!=true) throw new Exception("Mã tài sản trùng lặp");
        }

        //Set thuoc tinh Ten theo id
        public function setName($id,$value){
             $data=array('Ten_tai_san'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Set thuoc tinh mo ta theo id
        public function setDescription($id,$value){
             $data=array('Description'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Set thuoc tinh kieu theo ID
        public function setType($id,$value){
             $data=array('Type'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Set thuoc tinh ngay bat dau mua theo ID
        public function setStartDate($id,$value){
             $data=array('Start Date'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Set thuoc tinh gia theo ID
        public function setPrice($id,$value){
             $data=array('Price'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Set thoi han bao hanh theo ID
        public function setWarrantyTime($id,$value){
             $data=array('WarrantyTime'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Set trang thai theo ID
        public function setStatus($id,$value){
             $data=array('Status'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Set vi tri theo ID
        public function setPlace($id,$value){
             $data=array('Place'=>$value);
             $this->update($data,'id = ' .(int)$id);
        }

        //Lay thong tin tai san theo ID
        public function getItemFromID($id){
            $id=(int)$id;
            $row = $this->fetchRow('ItemID = ' . $id);
            if (!$row) {
               throw new Exception("Không tìm thấy tài sản theo yêu cầu");
            }
            return $row->toArray();
        }

        //Lay thong tin tai san theo Ma
        public function getItemFromMa($Ma){
             $row = $this->fetchRow('Ma_tai_san = ' . $Ma);
             if (!$row) {
                throw new Exception("Không tìm thấy tài sản có mã là $Ma");
            }
            return $row->toArray();
        }
    }
?>
