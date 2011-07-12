<?php

class Front_MailController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
//        $form = new Front_Form_Mail();
//        $this->view->form = $form;
//        $mail = new Zend_Mail();
//        $mail->addTo('ngocoanh.bk52@gmail.com', 'Ngoc Oanh')
//                ->setSubject('Test Zend_Mail')
//                ->setBodyHtml('<h3>Test Zend_Mail body</h3>');
//        if ($mail->send()) {
//            echo "OK";
//        } else {
//            echo "Not OK";
//        }
    }

    public function sendAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('Name');
            $email = $this->getRequest()->getPost('Email');
            $subject = $this->getRequest()->getPost('Subject');
            $content = $this->getRequest()->getPost('Content');
            $html = $content;
            $mail = new Zend_Mail();
            $mail->addTo($email, $name)
                    ->setSubject($subject)
                    ->setBodyHtml($html);
            if ($mail->send()) {
                $status = 'Success';
                $msg = 'Send email success.';
            } else {
                $status = 'Error';
                $msg = 'Send email fault.';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value.';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

}