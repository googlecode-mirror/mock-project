<?php

/**
 * QLTS v1
 * 
 * @version 1.0
 * @license
 */

/**
 * Class Asset_Model_DbTable_Message
 * 
 * @package Application/Module/Asset
 * @version 1.0
 * @author OanhNN
 * 
 * @todo Model data from iteminfor table in database
 */
class Asset_Model_DbTable_Message extends Zend_Db_Table_Abstract {

    /**
     * Database table name
     * @var $_name String
     */
    protected $_name = 'messageinfor';

    /**
     * Add new message
     * 
     * @param $SendID       Int
     * @param $ReceiveID    Int
     * @param $Title        String
     * @param $Detail       String
     * @return  boolen
     */
    public function addMessage($SendID, $ReceiveID, $Title, $Detail) {
        $data = array(
            'SendID' => (int) $SendID,
            'ReceiveID' => (int) $ReceiveID,
            'Title' => $Title,
            'Detail' => $Detail,
            'Time' => date("Y-m-d H:i:s"),
            'Readed' => 0
        );
        if ($this->insert($data)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Edit message
     * 
     * @param $MessageID    Int
     * @param $Title        String
     * @param $Detail       String
     * @param $Readed       Enum(0,1)
     * @return  integer
     *  0  Message is edited success
     *  1  Not exist message
     *  2  Message is readed
     *  3  Message is edited fail
     */
    public function editMessage($MessageID, $Title, $Detail, $Readed) {
        if ($this->checkMessage($MessageID) == 0) {
            return 1;
        }
        if ($this->isReaded($MessageID) == TRUE) {
            return 2;
        }
        $data = array(
            'Title' => $Title,
            'Detail' => $Detail,
            'Readed' => $Readed
        );
        if ($this->update($data, "MessageID = '$MessageID'")) {
            return 0;
        }
        return 3;
    }

    /**
     * Delete message
     * @param $MessageID
     * @return integer
     * 0 delete success
     * 1 message not exist
     * 2 delete fail
     */
    public function deleteMessage($MessageID) {
        if ($this->checkMessage($MessageID) == 0) {
            return 1;
        }
        if ($this->delete("MessageID = '$MessageID'") != 0) {
            return 0;
        }
        return 2;
    }

    /**
     * Get information of a message
     * 
     * @param $MessageID int
     * @return null|array
     */
    public function getMessage($MessageID) {
        $MessageID = (int) $MessageID;
        $row = $this->fetchRow("MessageID = '$MessageID'");
        if ($row == NULL) {
            return NULL;
        }
        return $row->toArray();
    }

    /**
     * Check message is readed
     * 
     * @param $MessageID
     * @return boolen
     */
    public function isReaded($MessageID) {
        $MessageID = (int) $MessageID;
        $row = $this->fetchRow("MessageID = '$MessageID'");
        if ($row == NULL) {
            return FALSE;
        }
        if ($row->Readed == 0) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Check exist message
     * 
     * @param $MessageID
     * @return Int
     * 0 if not exist message
     * MessageID if exist message
     */
    public function checkMessage($MessageID) {
        $MessageID = (int) $MessageID;
        $row = $this->fetchRow("MessageID = '$MessageID'");
        if ($row == NULL) {
            return 0;
        }
        return $row->MessageID;
    }

}

