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
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $role = Zend_Auth::getInstance()->getIdentity()->Role;
            switch ($role) {
                case 0: // Super Admin
                    $this->_redirect('/user/user/list');
                    break;
                case 1: // Admin
                    $this->_redirect('/asset/request/list/mode/2');
                    break;
                case 2: // IT
                    $this->_redirect('/asset/loan/list/mode/2');
                    break;
                default : // User
                    $this->_redirect('/asset/loan/list/mode/2');
                    break;
            }
        } else {
            $this->_redirect('/front/auth/login');
        }
    }

    public function aboutAction() {
    }

}
