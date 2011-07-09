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

        include_once APPLICATION_PATH . '/modules/User/models/DbTable/Member.php';
        $members = new User_Model_DbTable_Member();
//        $data = new Zend_Db_Table('memberinfor');
//        $members = $data->select()->from('memberinfor');
        
        $grid = new Ingot_JQuery_JqGrid('bookshelf',
                        new Ingot_JQuery_JqGrid_Adapter_DbTableSelect($members->select()));
        $col = new Ingot_JQuery_JqGrid_Column('UserID');
        $col->setLabel('User Id');
        $grid->addColumn($col);
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('Username'));
//        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('Role'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('FullName'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('Email'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('Group'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('Birthday'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('Phone'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('Address'));

//                 $footer = new Ingot_JQuery_JqGrid_Plugin_Pager();

        $grid->registerPlugin(new Ingot_JQuery_JqGrid_Plugin_ToolbarFilter());
        $this->view->grid = $grid->render();
    }

    public function aboutAction() {
        
    }

}
