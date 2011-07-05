<?php

class User_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function preDispatch() {
        // check login
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_redirect('/front/auth/login');
        }
    }

    public function indexAction() {
        // action body
    }

}

