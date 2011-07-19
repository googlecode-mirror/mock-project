<?php

class Front_AuthController extends Zend_Controller_Action {

    public function init() {
        
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

    public function loginAction() {
        $this->_helper->layout->setLayout('login');
//        $this->_helper->layout->disableLayout();
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/index');
        }
//        $form = new Front_Form_Login();
        if ($this->_request->isPost()) {

//            if ($form->isValid($this->_request->getPost())) {
//            
//                $uname = $form->getValue('username');
//                $passwd = $form->getValue('password');
            if (isset($_POST['txtUsername']) && isset($_POST['txtPassword'])) {
                $uname = $_POST['txtUsername'];
                $passwd = $_POST['txtPassword'];
                $authAdapter = $this->getAuthAdapter();
                $authAdapter->setIdentity($uname)
                        ->setCredential($this->encodePassword($passwd));
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
                        $this->_redirect('/front/index/index');
                    }
                }
            }
        }
//        $this->view->form = $form;
//        $this->view->messages = $form->getMessages();
    }

    public function successAction() {
        $this->_redirect('/front/index/index');
//        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
//            $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();
//        } else {
//            $this->_redirect('/front/index/index');
//        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect('/front/auth/login');
    }

    public function nopermissionAction() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->view->showMessegers = TRUE;
        } else {
            $this->_helper->getHelper('FlashMessenger')->addMessage('Please login.');
            $this->_redirect('/front/auth/login');
        }
    }

    private function getAuthAdapter() {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('memberinfor') // set users table name
                ->setIdentityColumn('username') // set username colum name
                ->setCredentialColumn('password'); // set password colum name
        return $authAdapter;
    }

    private function encodePassword($passwd) {
        return hash('sha256', 'hedspi' . $passwd . 'isk52');
//        return $passwd;
    }

}

