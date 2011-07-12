<?php

/**
 * QLTS v1
 * 
 * @version 1.0
 * @license
 */

/**
 * Class User_Model_DbTable_Member
 * 
 * @package Application/Module/User
 * @version 1.0
 * @author TuanNA18
 * 
 * @todo Model data from iteminfor table in database
 */
class Asset_Model_DbTable_Item extends Zend_Db_Table_Abstract {

    /**
     * @var $_name String
     */
    protected $_name = 'iteminfor';     //Ten bang thao tac

    /**
     * function addItem()
     * 
     * @todo add new item
     * @param $MaTS             String
     * @param $TenTS            String
     * @param $Description      String
     * @param $Type             Enum(0,1)
     * @param $StartDate        Date
     * @param $Price            Int
     * @param $WarrantyTime     Int
     * @param $Status           Enum(0,1,2)
     * @param $Place            String
     * @return   1  success
     * @return   0  error not insert
     * @return  -1  error MaTS exist
     */

    public function addItem($MaTS, $TenTS, $Description, $Type, $StartDate, $Price, $WarrantyTime, $Status, $Place) {

        if ($this->checkMaTS($MaTS) != NULL) {
            return -1;
        }

        $data = array(
            'Ma_tai_san' => $MaTS,
            'Ten_tai_san' => $TenTS,
            'Description' => $Description,
            'Type' => $Type,
            'StartDate' => $StartDate,
            'Price' => (int) $Price,
            'WarrantyTime' => (int) $WarrantyTime,
            'Status' => $Status,
            'Place' => $Place
        );
        if ($this->insert($data)) {
            return 1;
        }
        return 0;
    }

    /**
     * function getItemFromID()
     * 
     * @todo get member info
     * @param $ItemID Int
     * @return array() | NULL
     */
    public function getItemFromID($ItemID) {
        $ItemID = (int) $ItemID;
        $row = $this->fetchRow("ItemID = '$ItemID'");
        if ($row == NULL) {
            return NULL;
        }
        return $row->toArray();
    }

    /**
     * function deleteItem()
     * 
     * @todo delete member
     * @param $ItemID int
     * @return boolen
     */
    public function deleteItem($ItemID) {
        $ItemID = (int) $ItemID;
        return $this->delete("ItemID = '$ItemID'");
    }

    /**
     * function addItem()
     * 
     * @todo add new item
     * @param $ItemID           Int
     * @param $MaTS             String
     * @param $TenTS            String
     * @param $Description      String
     * @param $Type             Enum(0,1)
     * @param $StartDate        Date
     * @param $Price            Int
     * @param $WarrantyTime     Int
     * @param $Status           Enum(0,1,2)
     * @param $Place            String
     * @return   1  success
     * @return   0  error not insert
     * @return  -1  error MaTS exist
     */
    public function editItem($ItemID, $MaTS, $TenTS, $Description, $Type, $StartDate, $Price, $WarrantyTime, $Status, $Place) {
        if ($ItemID == NULL) {
            return 0;
        }
        $ItemID = (int) $ItemID;
        if ($this->checkMaTS($MaTS) != $ItemID) {
            return -1;
        }
        $data = array(
            'Ma_tai_san' => $MaTS,
            'Ten_tai_san' => $TenTS,
            'Description' => $Description,
            'Type' => $Type,
            'StartDate' => $StartDate,
            'Price' => (int) $Price,
            'WarrantyTime' => (int) $WarrantyTime,
            'Status' => $Status,
            'Place' => $Place
        );
        if ($this->update($data, "ItemID = '$ItemID'")) {
            return 1;
        }
        return 0;
    }

    //Lay thong tin tai san theo Ma
    public function getItemFromMa($MaTS) {
        $row = $this->fetchRow("Ma_tai_san = '$MaTS'");
        if ($row == NULL) {
            return NULL;
        }
        return $row->toArray();
    }

    //Lay thong tin tai san tu cac thong tin khac
    public function getItemFromOthers($Ten, $Type, $Status, $Place, $StartPrice, $EndPrice, $StartTime, $EndTime, $StartDate, $EndDate) {
        $sql = '1=1';
        if ($Ten != null) {
            $sql = $sql . ' AND Ten_tai_san LIKE %' . $Ten . '%';
        }
        if ($Type != null) {
            $sql = $sql . ' AND Type = ' . $Type;
        }
        if ($Status != null) {
            $sql = $sql . ' AND Status = ' . $Status;
        }
        if ($Place != null) {
            $sql = $sql . ' AND Place LIKE %' . $Place . '%';
        }
        if ($StartPrice != null) {
            $sql = $sql . ' AND Price >= ' . $this->_db->quote($StartPrice, 'INTEGER');
        }
        if ($EndPrice != null) {
            $sql = $sql . ' AND Price <= ' . $this->_db->quote($EndPrice, 'INTEGER');
        }
        if ($StartTime != null) {
            $sql = $sql . ' AND WarrantyTime >= ' . $this->_db->quote($StartTime, 'INTEGER');
        }
        if ($EndTime != null) {
            $sql = $sql . ' AND WarrantyTime <= ' . $this->_db->quote($EndTime, 'INTEGER');
        }
        if ($StartDate != null) {
            $sql = $sql . ' AND StartDate >= ' . $StartDate;
        }
        if ($EndDate != null) {
            $sql = $sql . ' AND StartDate <= ' . $EndDate;
        }
        $sql = $sql . ';';
        $result = $this->fetchAll($this->select()->where($sql));
        if (!$result) {
            return null;
        }
        return $result;
    }

    /**
     * function checkMaTS()
     * 
     * @todo check exist Ma_tai_san
     * @param $MaTS String
     * @return NULL if not exist
     * @return ItemID if exist
     */
    private function checkMaTS($MaTS) {
//        $MaTS = (int) $MaTS;
        $row = $this->fetchRow("Ma_tai_san = '$MaTS'");
        if ($row == NULL) {
            return NULL;
        }
        return $row->ItemID;
    }

}

