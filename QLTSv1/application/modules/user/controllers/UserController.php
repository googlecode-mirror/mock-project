<?php

class User_UserController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function preDispatch() {
        // check quyen SuperAdmin
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_redirect('/front/auth/login');
        } elseif ($auth->getIdentity()->Role != 0) {
            // TODO link den trang bao loi ko du quyen
            $this->_helper->getHelper('FlashMessenger')->addMessage("You haven't permission.");
//            $this->_redirect('/user/' . Zend_Auth::getInstance()->getIdentity()->username);
            $this->_redirect('/front/auth/nopermission');
        }
    }

    public function listAction() {
        include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        $members = new User_Model_DbTable_Member();
        $this->view->members = $members->fetchAll();
    }

    public function detailAction() {
        $UserID = $this->_getParam('UserID', -1);
        if ($UserID > 0) {
            include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
            $member = new User_Model_DbTable_Member();
            $this->view->member = $member->getMember($UserID);
        } else {
            // ko ton tai user
        }
    }

    public function addAction() {

        include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        include_once APPLICATION_PATH . '/modules/user/forms/User.php';
        $form = new User_Form_User();
//        $form->submit->setLabel('Add');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $username = $form->getValue('Username');
                $password = $form->getValue('Password');
                $role = $form->getValue('Role');
                $fullname = $form->getValue('FullName');
                $email = $form->getValue('Email');
                $group = $form->getValue('Group');
                $phone = $form->getValue('Phone');
                $address = $form->getValue('Address');
                $member = new User_Model_DbTable_Member();
                $return = $member->addMember($username, $password, $role, $email, $fullname, $group, $phone, $address);
                switch ($return) {
                    case -1: // loi email da ton tai
                        break;
                    case -2: // loi user da ton tai
                        break;
                    case 0: // loi ko add
                        break;
                    default : // update thanh cong
                        $this->_redirect('/user/user/list');
                        break;
                }
            } else {
                $form->populate($formData);
            }
        }
    }

    public function editAction() {
        include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        include_once APPLICATION_PATH . '/modules/user/forms/User.php';

        $form = new User_Form_User();
//        $form->submit->setLabel('Save');
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
                        $this->_redirect('/user/user/detail/UserID/' . $UserID);
                        break;
                }
            } else {
                $form->populate($formData);
            }
        } else {
            $UserID = (int) $this->_getParam('UserID', -1);
            if ($UserID > 0) {
                $member = new User_Model_DbTable_Member();
                $form->populate($member->getMember($UserID));
            } else {
                // ko ton tai UserID
            }
        }
    }

    public function deleteAction() {
        $UserID = (int) $this->_getParam('UserID', -1);
        if ($UserID > 0) {
            include_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
            $member = new User_Model_DbTable_Member;
            $member->deleteMember($UserID);
            $this->_redirect('/user/user/list');
        }
    }

}
