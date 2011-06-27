<?php

class Front_AuthController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

//    public function preDispatch() {
//        if (Zend_Auth::getInstance()->hasIdentity()) {
//            // logined
//            if ('login' == $this->getRequest()->getActionName()) {
//                $this->_helper->redirect('/home');
//            }
//        } else {
//            if ('login' != $this->getRequest()->getActionName()) {
//                $this->_helper->redirect('/login');
//            }
//        }
//    }

    public function indexAction() {
        $this->_forward('login');
    }

    public function loginAction() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
//            $this->_redirect('/home');
        }
        $form = new Front_Form_Login();
        if ($this->_request->isPost()) {

            if ($form->isValid($this->_request->getPost())) {
                $uname = $form->getValue('username');
                $passwd = $form->getValue('password');

                $authAdapter = $this->getAuthAdapter();
                $authAdapter->setIdentity($uname)
                        ->setCredential(User_Model_DbTable_Uses::encodePassword($passwd));
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $identity = $authAdapter->getResultRowObject();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);

                    $session = new Zend_Session_Namespace('auth');
                    $session->user = $authAdapter->getResultRowObject();

                    // redirect to original request URL if present
                    if (isset($session->requestURL)) {
                        $url = $session->requestURL;
                        unset($session->requestURL);
                        $this->_redirect($url);
                    } else {
                        // login successfully
                        $this->_helper->getHelper('FlashMessenger')->addMessage('You were successfully logged in.');
                        //                        $this->_redirect('/user/' . Zend_Auth::getInstance()->getIdentity()->username);
                        $this->_redirect('/login/success');
                    }
                }
            }
        }
        $this->view->form = $form;
        $this->view->messages = $form->getMessages();
    }

    public function successAction() {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
            $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();
        } else {
//            $this->_redirect('/profile');
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect('/home');
    }

    private function getAuthAdapter() {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('users') // set users table name
                ->setIdentityColumn('username') // set username colum name
                ->setCredentialColumn('password'); // set password colum name
        return $authAdapter;
    }

}

