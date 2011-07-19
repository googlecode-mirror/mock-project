<?php

class User_ProfileController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function detailAction() {

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $userInfo = Zend_Auth::getInstance()->getIdentity();
        } else {
            $this->_helper->getHelper('FlashMessenger')->addMessage("You haven't permission.");
            $this->_redirect('/front/auth/nopermission');
        }
        include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        $member = new User_Model_DbTable_Member();
        $meInfo = $member->getMember($userInfo->UserID);
        unset ($meInfo['Password']);
        $this->view->member = $meInfo;
        
    }

    public function editAction() {

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $userInfo = Zend_Auth::getInstance()->getIdentity();
        } else {
            $this->_helper->getHelper('FlashMessenger')->addMessage("You haven't permission.");
            $this->_redirect('/front/auth/nopermission');
        }
        include_once APPLICATION_PATH . '/modules/user/forms/Profile.php';
        $form = new User_Form_Profile;
        //$form->submit->setLabel('Save');
        $this->view->form = $form;
        include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $UserID = (int) $form->getValue('UserID');
                if ($UserID != $userInfo->UserID) {
                    $this->_helper->getHelper('FlashMessenger')->addMessage("You haven't permission.");
                    $this->_redirect('/front/auth/nopermission');
                }
                $username = $form->getValue('Username');
                $password = $form->getValue('Password');
                $repassword = $form->getValue('RePassword');
                $role = $userInfo->Role;
                $fullname = $form->getValue('FullName');
                $email = $form->getValue('Email');
                $birthday = $form->getValue('Birthday');
                $group = $form->getValue('Group');
                $phone = $form->getValue('Phone');
                $address = $form->getValue('Address');
                if ($password == $repassword) {
                    $password = $this->encodePassword($repassword);
                    $member = new User_Model_DbTable_Member();
                    $return = $member->editMember($UserID, $username, $password, $role, $email, $birthday, $fullname, $group, $phone, $address);
                    switch ($return) {
                        case -1: // loi email da ton tai
                            break;
                        case -2: // loi user da ton tai
                            break;
                        case 0: // loi ko update dc
                            break;
                        default : // update thanh cong
                            $this->_redirect('/user/profile/detail/UserID/' . $UserID);
                            break;
                    }
                }
            } else {
                $form->populate($formData);
            }
        } else {
            $member = new User_Model_DbTable_Member();
            $form->populate($member->getMember($userInfo->UserID));
        }
    }
    private function encodePassword($passwd) {
        return hash('sha256', 'hedspi' . $passwd . 'isk52');
//        return $passwd;
    }

}
