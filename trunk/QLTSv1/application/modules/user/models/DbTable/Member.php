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
 * @author OanhNN
 * 
 * @todo Model data from memberinfo table in database
 */
class User_Model_DbTable_Member extends Zend_Db_Table_Abstract {

    /**
     * @var $_name String
     */
    protected $_name = 'memberinfor';

    /**
     * function addMember()
     * 
     * @todo add new member
     * @param $username String
     * @param $password String
     * @param $role     Enum(0, 1, 2, 3)
     * @param $email    String
     * @param $fullname String
     * @param $group    String
     * @param $phone    String
     * @param $address  String
     * @return   1  success
     * @return   0  error not insert
     * @return  -1  error email exist
     * @return  -2  error username exist
     */
    public function addMember($username, $password, $role, $email, $fullname, $group, $phone, $address) {
        // check exit email
        if ($this->checkEmail($email) != NULL) {
            return -1;
        }
        // check exit user
        if ($this->checkUsername($username) != NULL) {
            return -2;
        }
        $data = array(
            'Username' => $username,
            'Password' => $password,
            'Role' => $role,
            'Email' => $email,
            'FullName' => $fullname,
            'Group' => $group,
            'Phone' => $phone,
            'Address' => $address
        );
        if ($this->insert($data)) {
            return 1;
        }
        return 0;
    }

    /**
     * function editMember()
     * 
     * @todo edit info of member
     * @param $UserID   Int
     * @param $username String
     * @param $password String
     * @param $role     Enum(0, 1, 2, 3)
     * @param $email    String
     * @param $fullname String
     * @param $group    String
     * @param $phone    String
     * @param $address  String
     * @return   1  sussecc
     * @return   0  error not update
     * @return  -1  error email exist
     * @return  -2  error username exist
     */
    public function editMember($UserID, $username, $password, $role, $email, $fullname, $group, $phone, $address) {
        if ($UserID == NULL){
            return (int) 0;
        }
        $UserID = (int) $UserID;
        // check exit email
        if ($this->checkEmail($email) != $UserID) {
            return (int) -1;
        }
        // check exit user
        if ($this->checkUsername($username) != $UserID) {
            return (int) -2;
        }
        $data = array(
            'UserID' => (int) $UserID,
            'Username' => $username,
            'Password' => $password,
            'Role' => $role,
            'Email' => $email,
            'FullName' => $fullname,
            'Group' => $group,
            'Phone' => $phone,
            'Address' => $address
        );
        if ($this->update($data, "UserID = '$UserID'")) {
            return (int) 1;
        }
        return (int) 0;
    }

    /**
     * function deleteMember()
     * 
     * @todo delete member
     * @param $UserID
     * @return boolen
     */
    public function deleteMember($UserID) {
        $UserID = (int) $UserID;
        return $this->delete("UserID = '$UserID'");
    }

    /**
     * function getMember()
     * 
     * @todo get member info
     * @param $UserID Int
     * @return array()
     */
    public function getMember($UserID) {
        $UserID = (int) $UserID;
        $row = $this->fetchRow("UserID = '$UserID'");
        if ($row == NULL) {
            return NULL;
        }
        return $row->toArray();
    }

    /**
     * function getMemberFromUsername()
     * 
     * @todo get member info
     * @param $username String
     * @return array()
     */
    public function getMemberFromUsername($username) {
        $row = $this->fetchRow("Username = '$username'");
        if ($row == NULL) {
            return NULL;
        }
        return $row->toArray();
    }

    /**
     * function checkEmail()
     * 
     * @todo check exist email
     * @param $email String
     * @return NULL not exist this email
     * @return UserID 
     */
    private function checkEmail($email) {
        $row = $this->fetchRow("Email = '$email'");
        if ($row == NULL) {
            return NULL; // email ok
        }
        return $row->UserID; // email exist => not ok
    }

    /**
     * function checkUsername()
     * 
     * @todo check exist username
     * @param $username String
     * @return NULL if not exist
     * @return UserID if exist
     */
    private function checkUsername($username) {
        $row = $this->fetchRow("Username = '$username'");
        if ($row == NULL) {
            return NULL; // username ok
        }
        return $row->UserID; // username not ok
    }

    /**
     * function encodePassword()
     * 
     * @todo get member info
     * @param $UserID Int
     * @return password encode
     */
    private function encodePassword($passwd) {
//        return hash('sha256', 'hedspi' . $passwd . 'isk52');
        return $passwd;
    }

}
