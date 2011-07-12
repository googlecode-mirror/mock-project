<?php

/**
 * QLTS v1
 * 
 * @version 1.0
 * @license
 */

/**
 * Class Asset_Model_DbTable_Loan
 * 
 * @package Application/Module/Asset
 * @version 1.0
 * @author TuanNA18
 * 
 * @todo Model data from iteminfor table in database
 */

require_once 'Zend/Db/Table/Abstract.php';
class Asset_Model_DbTable_Loan extends Zend_Db_Table_Abstract {

    /**
     * @var $_name String
     */
    protected $_name = 'loaninfor';       //Ten bang du lieu thao tac

    /**
     * function addILoan()
     * 
     * @todo add new item
     * @param $MaTS             String
     * @param $UserID           Int
     * @param $Detail           String
     * @param $Data             Date
     * @return   1  success
     * @return   0  error not insert
     * @return  -1  error MaTS exist
     */

    public function addLoan($MaTS, $UserID, $Detail, $Date) {
        if ($this->checkItem($MaTS)) {
            return -1;
        }
        $data = array(
            'Ma_tai_san' => $MaTS,
            'UserID' => $UserID,
            'Detail' => $Detail,
            'Date' => $Date
        );
        if ($this->insert($data)) {
            return 1;
        }
        return 0;
    }

    /**
     * function editLoan()
     * 
     * @todo add new item
     * @param $MaTS             String
     * @param $UserID           Int
     * @param $Detail           String
     * @param $Data             Date
     * @return   1  success
     * @return   0  error not insert
     * @return  -1  error MaTS exist
     */
    public function editLoan($MaTS, $UserID, $Detail, $Date) {
        $data = array(
            'UserID' => $UserID,
            'Detail' => $Detail,
            'Date' => $Date,
        );
        if ($this->update($data, "Ma_tai_san='$MaTS'")) {
            return 1;
        }
        return 0;
    }

    /**
     * function deleteLoan()
     * 
     * @todo add new item
     * @param $MaTS             String
     * @return   boolen
     */
    public function deleteLoan($MaTS) {
        return $this->delete("Ma_tai_san='$MaTS'");
    }

    /**
     * function deleteLoan()
     * 
     * @todo add new item
     * @param $MaTS             String
     * @return   boolen
     */
    public function getLoanFromMa($MaTS) {
        $row = $this->fetchRow("Ma_tai_san = '$MaTS'");
        if ($row == NULL) {
            return NULL;
        }
        return $row->toArray();
    }

    //Get du lieu theo cac yeu to khac
    public function getLoanFromOthers($UserID, $StartDate, $EndDate) {
        $sql = '1=1';
        if ($UserID != null) {
            $sql = $sql . ' AND UserID = ' . $this->_db->quote($UserID, 'INTEGER');
        }
        if ($StartDate != null) {
            $sql = $sql . ' AND Date >= ' . $this->_db->quote($StartDate);
        }
        if ($EndDate != null) {
            $sql = $sql . ' AND Date <= ' . $this->_db->quote($EndDate);
        }
        $result = $this->fetchAll($this->select()->where($sql));
        if (count($result)==0) {
            return null;
        }
        return $result;
    }

    public function getLoanFromUsername($Name, $StartDate, $EndDate) {
        $sql = '1=1';
        if ($Name != null) {
            $sql = $sql . ' AND m.Username LIKE %' . $Name . '%';
        }
        if ($StartDate != null) {
            $sql = $sql . ' AND loaninfor.Date >= ' . $StartDate;
        }
        if ($EndDate != null) {
            $sql = $sql . ' AND loaninfor.Date <= ' . $EndDate;
        }
        $select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                ->setIntegrityCheck(false);
        $result = $this->fetchAll($select->join('memberinfor as m', 'm.UserID = loaninfor.UserID', 'Username')
                                ->where($sql));
        if (count($result)==0) {
            return null;
        }
        return $result;
    }

    public function checkItem($MaTS) {
        $row = $this->fetchRow("Ma_tai_san='$MaTS'");
        if ($row == NULL) {
            return FALSE;
        }
        return TRUE;
    }

}

?>
