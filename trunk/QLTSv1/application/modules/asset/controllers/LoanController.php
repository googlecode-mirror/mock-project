<?php

class Asset_LoanController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function addAction() {

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Loan.php';
        require_once APPLICATION_PATH . '/modules/asset/forms/Loan.php';
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Loan.php';
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';

        $form = new Asset_Form_Loan();
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $memberInfo = Zend_Auth::getInstance()->getIdentity();
            
        } else {
            // chua dang nhap
        }
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $MaTS = $form->getValues('MaTS');
                $UserID = $form->getValue('UserID');
                $Detail = $form->getValues('Detail');
                $Date = date("m/d/Y", ($form->getValue('Date')));

                $item = new Asset_Model_DbTable_Item();
                $loan = new Asset_Model_DbTable_Loan();
                $history = new Asset_Model_DbTable_History();
                
                $item->getItemFromMa($MaTS);
                $loan->addLoan($MaTS, $UserID, $Detail, $Date);
                $history->addHistory($memberInfo->UserID, $UserID, $ItemID, $Detail, $Date);
                switch ($return) {
                    case -1: // loi email da ton tai
//                        break;
                    case -2: // loi user da ton tai
//                        break;
                    case 0: // loi ko update dc
//                        break;
                    default : // update thanh cong
                        $this->_redirect('/asset/loan/list');
                        break;
                }
            } else {
                $form->populate($formData);
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $MaTS = (int) $this->_getParam('MaTS', -1);
        // TODO : xu ly confirm delete
        if ($MaTS > 0) {
            require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Loan.php';
            $loan = new Asset_Model_DbTable_Loan();
            $this->_redirect('/asset/loan/list');
        } else {
            // ko ton tai item
        }
    }

    public function listAction() {
        
    }

    public function recordsAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Loan.php';
        $loan = new Asset_Model_DbTable_Loan();

        $sort_column = $this->_getParam('sortname', 'Ma_tai_san'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Ma_tai_san');
        $search_for = $this->_getParam('query', '');

        $select = $loan->select()->order("$sort_column $sort_order")->limit($limit, $offset);
//        Zend_Debug::dump($select);
//        exit ();

        if (!empty($search_column) && !empty($search_for)) {
            $select->where($search_column . ' LIKE ?', '%' . $search_for . '%');
        }

        $pager = Zend_Paginator::factory($select);
        $pager->setCurrentPageNumber($page);
        $pager->setItemCountPerPage($limit);
        $records = $pager->getIterator();

        foreach ($records AS $record) {
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $rows[] = array('id' => $record['Ma_tai_san'],
                'cell' => $record->toArray()
            );
        }

        $this->getResponse()
                ->setHeader('Content-Type', 'application/json');

        $jsonData = array(
            'page' => $page,
            'total' => $pager->getTotalItemCount(),
            'rows' => $rows
        );
        echo Zend_Json::encode($jsonData);
    }

}
