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
        require_once APPLICATION_PATH . '/modules/user/forms/User.php';
        $form = new User_Form_User();
        $this->view->form = $form;
    }

    public function detailAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $UserID = (int) $this->getRequest()->getPost('UserID', -1);
            if ($UserID > 0) {
                require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
                $user = new User_Model_DbTable_Member();
                $status = 'success';
                $data = (array) $user->getMember($UserID);
                unset($data['Password']);
                echo Zend_Json::encode(array('status' => $status, 'data' => $data));
            } else {
                $status = 'error';
                $msg = 'Not found this user.';
                echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
            }
        } else {
            $status = 'error';
            $msg = 'Not found POST value.';
            echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
        }
    }

    public function addAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        require_once APPLICATION_PATH . '/modules/user/forms/User.php';
        $form = new User_Form_User();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $username = $form->getValue('Username');
                $password = $this->encodePassword($username . '12345');
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
                        $status = 'error';
                        $msg = 'Email address is exist';
                        break;
                    case -2: // loi user da ton tai
                        $status = 'error';
                        $msg = 'Username is exist';
                        break;
                    case 0: // loi ko add
                        $status = 'error';
                        $msg = 'Cannot add this user';
                        break;
                    case 1:
                    default : // update thanh cong
                        $status = 'success';
                        $msg = 'Add user success';
//                        $this->_redirect('/user/user/list');
                        break;
                }
            } else {
                $status = 'error';
                $msg = 'Not found POST value';
            }
        } else {
            $status = 'error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function editAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        require_once APPLICATION_PATH . '/modules/user/forms/User.php';
        $form = new User_Form_User();
        $member = new User_Model_DbTable_Member();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $UserID = (int) $form->getValue('UserID');
                $userInfor = $member->getMember($UserID);
                $username = $form->getValue('Username');
                $password = $userInfor['Password'];
                $role = $form->getValue('Role');
                $fullname = $form->getValue('FullName');
                $email = $form->getValue('Email');
                $group = $form->getValue('Group');
                $phone = $form->getValue('Phone');
                $address = $form->getValue('Address');

                $return = $member->editMember($UserID, $username, $password, $role, $email, $fullname, $group, $phone, $address);
                switch ($return) {
                    case -1: // loi email da ton tai
                        $status = 'error';
                        $msg = 'Email address is exist';
                        break;
                    case -2: // loi user da ton tai
                        $status = 'error';
                        $msg = 'Username is exist';
                        break;
                    case 0: // loi ko add
                        $status = 'error';
                        $msg = 'Cannot edit this user';
                        break;
                    case 1:
                    default : // update thanh cong
                        $status = 'success';
                        $msg = 'Edit user\'s information success';
//                        $this->_redirect('/user/user/list');
                        break;
                }
            } else {
                $status = 'error';
                $msg = 'Not found POST value';
            }
        } else {
            $status = 'error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $UserID = (int) $this->getRequest()->getPost('UserID', -1);
            if ($UserID > 0) {
                require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
                require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Loan.php';
                $member = new User_Model_DbTable_Member();
                $loan = new Asset_Model_DbTable_Loan();
                if ($loan->fetchRow("UserID = '$UserID'")) {
                    $status = 'error';
                    $msg = 'User van con muon TS';
                } else {
                    $member->deleteMember($UserID);
                    $status = 'success';
                    $msg = 'Delete user success';
                }
            } else {
                $status = 'error';
                $msg = 'Not found this user.';
            }
        } else {
            $status = 'error';
            $msg = 'Not found POST value.';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function recordsAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        $members = new User_Model_DbTable_Member();

        $sort_column = $this->_getParam('sortname', 'UserID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Username');
        $search_for = $this->_getParam('query', '');

        $select = $members->select()->order("$sort_column $sort_order")->limit($limit, $offset);

        if (!empty($search_column) && !empty($search_for)) {
            $select->where($search_column . ' LIKE ?', '%' . $search_for . '%');
        }

        $pager = Zend_Paginator::factory($select);
        $pager->setCurrentPageNumber($page);
        $pager->setItemCountPerPage($limit);
        $records = $pager->getIterator();

        foreach ($records AS $record) {
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $rows[] = array('id' => $record['UserID'],
                'cell' => $record->toArray()
            );
        }

        $this->getResponse()
                ->setHeader('Content-Type', 'application/json');

        $jsonData = array(
            'page' => $page,
            'total' => $pager->getTotalItemCount(),
            'rows' => $rows
        );
        echo Zend_Json::encode($jsonData);
    }

    private function encodePassword($passwd) {
//        return hash('sha256', 'hedspi' . $passwd . 'isk52');
        return $passwd;
    }
}
