<?php
class Front_MailController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }
    
    public function indexAction(){
        $mail = new Zend_Mail();
        $mail->setFrom('ngocoanh.bk52@gmail.com', '')
                ->addTo('ngocoanh.bk52@gmail.com', 'Ngoc Oanh')
                ->setReplyTo('ngocoanh.bk52@gmail.com', 'OanhNN')
                ->setSubject('Test Zend_Mail')
                ->setBodyHtml('<h3>Test Zend_Mail body</h3>');
        if ($mail->send()) {
            echo "OK";
        } else {
            echo "Not OK";
        }
    }
    

}