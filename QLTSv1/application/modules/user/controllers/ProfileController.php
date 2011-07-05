<?php

class User_ProfileController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function detailAction() {
        // get UserID from param
        $UserID = $this->_getParam('UserID', -1);
        if ($UserID == Zend_Auth::getInstance()->getIdentity()->UserID) {
            include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
            $member = new User_Model_DbTable_Member();
            $this->view->member = $member->getMember($UserID);
        } else {
            $this->_helper->getHelper('FlashMessenger')->addMessage("You haven't permission.");
//            $this->_redirect('/user/' . Zend_Auth::getInstance()->getIdentity()->username);
            $this->_redirect('/front/auth/nopermission');
        }
    }

    public function editAction() {
        include_once APPLICATION_PATH . '/modules/user/forms/Profile.php';
        $form = new User_Form_Profile;
        //$form->submit->setLabel('Save');
        $this->view->form = $form;
        include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $UserID = (int) $form->getValue('UserID');
                $username = $form->getValue('Username');
                $password = $form->getValue('Password');
                $role = $form->getValue('Role');
                $fullname = $form->getValue('FullName');
                $email = $form->getValue('Email');
                $group = $form->getValue('Group');
                $phone = $form->getValue('Phone');
                $address = $form->getValue('Address');
                $member = new User_Model_DbTable_Member();
                $return = $member->editMember($UserID, $username, $password, $role, $email, $fullname, $group, $phone, $address);
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
            } else {
                $form->populate($formData);
            }
        } else {
            $UserID = (int) $this->_getParam('UserID', -1);
            if ($UserID == Zend_Auth::getInstance()->getIdentity()->UserID) {
                $member = new User_Model_DbTable_Member();
                $form->populate($member->getMember($UserID));
            } else {
                $this->_helper->getHelper('FlashMessenger')->addMessage("You haven't permission.");
//                $this->_redirect('/user/' . Zend_Auth::getInstance()->getIdentity()->username);
                $this->_redirect('/front/auth/nopermission');
            }
        }
    }

}
