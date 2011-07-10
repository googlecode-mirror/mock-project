<?php

class Front_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

//    public function preDispatch() {
//        if (Zend_Auth::getInstance()->hasIdentity()) {
//            // logined
//            if ('login' == $this->getRequest()->getActionName()) {
//                $this->_helper->redirect('/index');
//            }
//        } else {
//            // not login
//            if ('login' != $this->getRequest()->getActionName()) {
//                $this->_helper->redirect('/auth/login');
//            }
//        }
//    }
    public function indexAction() {
        // action body
        echo "Hello world <br />";
//        require_once APPLICATION_PATH . '/modules/user/forms/User.php';
//        $form = new User_Form_User();
//        echo $form;
        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        $user = new User_Model_DbTable_Member();
        $status = 'success';
        $data = (array) $user->getMember(2);
        unset($data['Password']);
        echo Zend_Json::encode(array('status' => $status, 'data' => $data));
    }

    public function aboutAction() {
        
    }

}
