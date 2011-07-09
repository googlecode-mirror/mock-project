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

    }

    public function aboutAction() {
        
    }

}
