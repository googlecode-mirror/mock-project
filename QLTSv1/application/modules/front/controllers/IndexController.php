<?php

class Front_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
        echo "Hello world <br />";

    }
    public function recordsAction(){
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $items = new Front_Model_DbTable_Item();
        $listItem = $items->getItem();
        $dojoData= new Zend_Dojo_Data('ItemID', $listItem, 'ItemID');
        echo $dojoData->toJson();
//        exit ();
    }

}
