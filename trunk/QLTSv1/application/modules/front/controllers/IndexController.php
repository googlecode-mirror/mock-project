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
            $this->_redirect('/asset/message/list');
        } else {
            $this->_redirect('/front/auth/login');
        }
    }

    public function aboutAction() {
        
    }

}
