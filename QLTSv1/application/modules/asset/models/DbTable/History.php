<?php

/**
 * QLTS v1
 *
 * @version 1.0
 * @license
 */
require_once 'Zend/Db/Table/Abstract.php';
/**
 * Class Asset_Model_DbTable_History
 *
 * @package Application/Module/Asset
 * @version 1.0
 * @author TuanNA18
 *
 * @todo Model data from iteminfor table in database
 */

class Asset_Model_DbTable_History extends Zend_Db_Table_Abstract {

    protected $_name = 'historyinfor';       //Ten bang thao tac

    //Them mot History voi cac thuoc tinh di kem

    public function addHistory($LUserID, $RUserID, $ItemID, $Detail, $Date) {
        $data = array(
            'LUserID' => (int) $LUserID,
            'RUserID' => (int) $RUserID,
            'ItemID' => (int) $ItemID,
            'Detail' => $Detail,
            'Date' => $Date
        );
        if ($this->insert($data)) {
            return 1;
        }
        return 0;
    }

    //Sua mot History voi cac thuoc tinh di kem
    public function editHistory($id, $LUserID, $RUserID, $ItemID, $Detail, $Date) {
        $id = (int) $id;
        $data = array(
            'LUserID' => $LUserID,
            'RUserID' => $RUserID,
            'ItemID' => $ItemID,
            'Detail' => $Detail,
            'Date' => $Date
        );
        $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
    }

    //Xoa History theo ID
    public function deleteHistory($id) {
        $this->delete('HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
    }

    public function setLUserID($id, $value) {
        $data = array('LUserID' => $value);
        $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
    }

    public function setRUserID($id, $value) {
        $data = array('RUserID' => $value);
        $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
    }

    public function setItemID($id, $value) {
        $data = array('ItemID' => $value);
        $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
    }

    public function setDetail($id, $value) {
        $data = array('Detail' => $value);
        $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
    }

    public function setDate($id, $value) {
        $data = array('Date' => $value);
        $this->update($data, 'HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
    }

    //Lay mot History ra theo ID
    public function getHistoryFromID($id) {
        $row = $this->fetchRow('HistoryID = ' . $this->_db->quote($id, 'INTEGER'));
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }

    //Lay cac History tu cac thong so khac
    public function getHistoryFromOthers($LUserID, $RUserID, $ItemID, $StartDate, $EndDate) {
        $sql = '1=1';
        if ($LUserID != null) {
            $sql = $sql . ' AND LUserID = ' . $this->_db->quote($LUserID, 'INTEGER');
        }
        if ($RUserID != null) {
            $sql = $sql . ' AND RUserID = ' . $this->_db->quote($RUserID, 'INTEGER');
        }
        if ($ItemID != null) {
            $sql = $sql . ' AND ItemID = ' . $this->_db->quote($ItemID, 'INTEGER');
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

}

?>
