<?php

/**
 * QLTS v1
 * 
 * @version 1.0
 * @license
 */

/**
 * Class Front_Model_DbTable_Message
 * 
 * @package Application/Module/Front
 * @version 1.0
 * @author OanhNN
 * 
 * @todo Model data from messageinfor table in database
 */
class Front_Model_DbTable_Message extends Zend_Db_Table_Abstract {

    /**
     * @var $_name String
     */
    protected $_name = 'memberinfor';

    /**
     * function addMessage()
     * 
     * @todo add new message
     * @param $sendID int
     * @param $receivelID int
     * @param $content String
     * @param $time timedate
     * @return boolen
     */
    public function addMessage($sendID, $receivelID, $content, $time) {
        
    }

    /**
     * function editMessage()
     * 
     * @todo edit message
     * @param $messageID int
     * @param $sendID int
     * @param $receivelID int
     * @param $content String
     * @param $time timedate
     * @return boolen
     */
    public function editMessage($messageID, $sendID, $receveivelID, $content, $time) {
        
    }

    /**
     * function deleteMessage()
     * 
     * @todo delete message
     * @param $messageID int
     * @return boolen
     */
    public function deleteMessage($messageID) {
        
    }

    /**
     * function getMessage()
     * 
     * @todo get message
     * @param $messageID int
     * @return array()
     */
    public function getMessage($messageID) {
        
    }

    /**
     * function readMessage()
     * 
     * @todo add new message
     * @param $messageID int
     * @return boolen
     */
    public function readMessage($messageID) {
        
    }

    /**
     * function getNewMessage()
     * 
     * @todo get list new message
     * @param $receivelID int
     * @return array()
     */
    public function getNewMessage($receivelID) {
        
    }

}