<?php

class Asset_LoanController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function addAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Loan.php';
        require_once APPLICATION_PATH . '/modules/asset/forms/Loan.php';
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/History.php';
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';

        $form = new Asset_Form_Loan();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $MaTS = $form->getValue('MaTS');
                $Username = $form->getValue('Username');
                $Detail = $form->getValue('Detail');
                $Date = date('Y-m-d');
                $Place = $form->getValue('Place');

                $item = new Asset_Model_DbTable_Item();
                $loan = new Asset_Model_DbTable_Loan();
                $history = new Asset_Model_DbTable_History();
                $user = new User_Model_DbTable_Member();

                $itemInfo = $item->getItemFromMa($MaTS);
                if ($itemInfo != NULL) {
                    $userInfo = $user->getMemberFromUsername($Username);
                    if ($userInfo != NULL) {
                        if ($history->addHistory(Zend_Auth::getInstance()->getIdentity()->UserID, $userInfo['UserID'], $itemInfo['ItemID'], $Detail, $Date) &&
                                $item->editItem($itemInfo['ItemID'], $MaTS, $itemInfo['Ten_tai_san'], $itemInfo['Description'], $itemInfo['Type'], $itemInfo['StartDate'], $itemInfo['Price'], $itemInfo['WarrantyTime'], 1, $Place) == 1 &&
                                $loan->addLoan($MaTS, $userInfo['UserID'], $Detail, $Date) == 1) {
                            $status = 'Success';
                            $msg = 'Update database success.';
                        } else {
                            $status = 'Error';
                            $msg = 'Update database fail.';
                        }
                    } else {
                        $status = 'Error';
                        $msg = 'Not found user';
                    }
                } else {
                    $status = 'Error';
                    $msg = 'Not found item';
                }
            } else {
                $status = 'Error';
                $msg = 'POST value format inaild.';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value.';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function deleteAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $MaTS = $this->getRequest()->getPost('MaTS');
            require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Loan.php';
            require_once APPLICATION_PATH . '/modules/asset/models/DBTable/History.php';
            require_once APPLICATION_PATH . '/modules/asset/models/DBTable/Item.php';
            $loan = new Asset_Model_DbTable_Loan();
            $history = new Asset_Model_DbTable_History();
            $item = new Asset_Model_DbTable_Item();
            $loanInfo = $loan->getLoanFromMa($MaTS);
            if ($loanInfo != NULL) {
                $itemInfo = $item->getItemFromMa($MaTS);
                if ($itemInfo != NULL) {
                    if ($history->addHistory($loanInfo['UserID'], Zend_Auth::getInstance()->getIdentity()->UserID, $itemInfo['ItemID'], 'Tra thiet bi', date('Y-m-d')) &&
                            $item->editItem($itemInfo['ItemID'], $MaTS, $itemInfo['Ten_tai_san'], $itemInfo['Description'], $itemInfo['Type'], $itemInfo['StartDate'], $itemInfo['Price'], $itemInfo['WarrantyTime'], 0, 'Kho') == 1 &&
                            $loan->deleteLoan($MaTS) != NULL) {
                        $status = 'Success';
                        $msg = 'Update database success.';
                    } else {
                        $status = 'Error';
                        $msg = 'Update database fail.';
                    }
                } else {
                    $status = 'Error';
                    $msg = 'Not found record';
                }
            } else {
                $status = 'Error';
                $msg = 'Not found record';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value.';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function listAction() {
        // phan chuc nang theo user type
        // Super Admin, Admin : add, delete, list
        // User, IT : list
        // phan chuc nang theo yeu cau
        // mode = 1 : list all item
        // mode = 2 : list all item minh muon (default)
        $this->view->mode = $this->_getParam('mode', 2);

        require_once APPLICATION_PATH . '/modules/asset/forms/Loan.php';
        $form = new Asset_Form_Loan();
        $this->view->form = $form;
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

        $mode = (int) $this->_getParam('mode', 2);

        switch ($mode) {
            case 1: // list all item
                $select = $loan->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('u' => 'memberinfor'), 'loaninfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                ->join(array('i' => 'iteminfor'), 'loaninfor.Ma_tai_san = i.Ma_tai_san', array('Ten_tai_san' => 'i.Ten_tai_san'))
                                ->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 2: // list all item user dang muon
            default :
                $uid = Zend_Auth::getInstance()->getIdentity()->UserID;
                $select = $loan->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                        ->setIntegrityCheck(false)
                        ->join(array('u' => 'memberinfor'), 'loaninfor.UserID = u.UserID', array('Username' => 'u.Username'))
                        ->join(array('i' => 'iteminfor'), 'loaninfor.Ma_tai_san = i.Ma_tai_san', array('Ten_tai_san' => 'i.Ten_tai_san'))
                        ->where("loaninfor.UserID = '$uid'")
                        ->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
        }
        

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
