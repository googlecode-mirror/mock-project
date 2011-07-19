<?php

class Asset_ItemController extends Zend_Controller_Action {

    public function init() {
        
    }
    
    public function addAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
//        require_once APPLICATION_PATH . '/modules/asset/forms/Item.php';

        $form = new Asset_Form_Item();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
//                $ItemID = (int) $form->getValue('ItemID');
                $maTS = $form->getValue('MaTS');
                $tenTS = $form->getValue('TenTS');
                $descr = $form->getValue('Description');
                $type = $form->getValue('Type');
                $startDate = $form->getValue('StartDate');
                $price = $form->getValue('Price');
                $warrantyTime = $form->getValue('WarrantyTime');
                $status = $form->getValue('Status');
                $place = $form->getValue('Place');
                $item = new Asset_Model_DbTable_Item();
                $return = $item->addItem($maTS, $tenTS, $descr, $type, $startDate, $price, $warrantyTime, $status, $place);
                switch ($return) {
                    case -1: // loi MaTS da ton tai
                        $status = 'Error';
                        $msg = 'MaTS is exist';
                        break;
                    case 0: // loi ko add dc
                        $status = 'Error';
                        $msg = 'Can\'t add this item';
                        break;
                    case 1:
                    default : // add thanh cong
                        $status = 'Success';
                        $msg = 'Add item success';
                        break;
                }
            } else {
                $status = 'Error';
                $msg = 'POST value format invaild';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function editAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
//        require_once APPLICATION_PATH . '/modules/asset/forms/Item.php';

        $form = new Asset_Form_Item();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $ItemID = (int) $form->getValue('ItemID');
                $maTS = $form->getValue('MaTS');
                $tenTS = $form->getValue('TenTS');
                $descr = $form->getValue('Description');
                $type = $form->getValue('Type');
                $startDate = $form->getValue('StartDate');
                $price = $form->getValue('Price');
                $warrantyTime = $form->getValue('WarrantyTime');
                $status = $form->getValue('Status');
                $place = $form->getValue('Place');
                $item = new Asset_Model_DbTable_Item();
                $return = $item->editItem($ItemID, $maTS, $tenTS, $descr, $type, $startDate, $price, $warrantyTime, $status, $place);
                switch ($return) {
                    case -1: // loi MaTS da ton tai
                        $status = 'Error';
                        $msg = 'MaTS is exist';
                        break;
                    case 0: // loi ko update dc
                        $status = 'Error';
                        $msg = 'Can\'t update this item';
                        break;
                    case 1:
                    default : // update thanh cong
                        $status = 'Success';
                        $msg = 'Update item success';
                        break;
                }
            } else {
                $status = 'Error';
                $msg = 'POST value format invaild';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function deleteAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $UserID = (int) $this->getRequest()->getPost('UserID', -1);
            $ItemID = (int) $this->getRequest()->getPost('ItemID', -1);
            if ($ItemID > 0) {
//                require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
                $item = new Asset_Model_DbTable_Item();
                $itemInfo = $item->getItemFromID($ItemID);
                if ($itemInfo['Status'] == 1) {
                    $status = 'Error';
                    $msg = 'Item nay dang duoc user muon';
                } else {
                    $re = $item->deleteItem($ItemID);
                    $status = 'Success';
                    $msg = 'Delete item success';
                }
            } else {
                $status = 'Error';
                $msg = 'Not found this item.';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value.';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function detailAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $ItemID = (int) $this->getRequest()->getPost('ItemID', -1);
            if ($ItemID > 0) {
//                require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
                $item = new Asset_Model_DbTable_Item();
                $status = 'success';
                $data = (array) $item->getItemFromID($ItemID);
                echo Zend_Json::encode(array('status' => $status, 'data' => $data));
            } else {
                $status = 'error';
                $msg = 'Not found this item.';
                echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
            }
        } else {
            $status = 'error';
            $msg = 'Not found POST value.';
            echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
        }
    }

    public function listAction() {
//        require_once APPLICATION_PATH . '/modules/asset/forms/Item.php';
        $form = new Asset_Form_Item();
        $this->view->form = $form;
    }

    public function recordsAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()
                ->setHeader('Content-Type', 'application/json');
//        require_once APPLICATCION_PATH . '/modules/asset/models/DbTable/Item.php';
        $items = new Asset_Model_DbTable_Item();

        $sort_column = $this->_getParam('sortname', 'ItemID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Ma_tai_san');
        $search_for = $this->_getParam('query', '');

        $mode = (int) $this->_getParam('mode', 1);
        switch ($mode) {
            case 1: // list all item
                $select = $items->select()->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 2: // list all item free
                $select = $items->select()->where("Status = '0'")->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 3: // list all item busy
                $select = $items->select()->where("Status = '1'")->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 4: // list all item corrupt
                $select = $items->select()->where("Status = '2'")->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            default :
                $select = $items->select()->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
        }



        if (!empty($search_column) && !empty($search_for)) {
            $select->where($search_column . ' LIKE ?', '%' . $search_for . '%');
        }

        $pager = Zend_Paginator::factory($select);
        $pager->setCurrentPageNumber($page);
        $pager->setItemCountPerPage($limit);
        $records = $pager->getIterator();
        $total = $pager->getTotalItemCount();
        if ($total == 0) {
            echo Zend_Json::encode(array('page' => $page, 'total' => $total, 'rows' => NULL));
            exit();
        }
        foreach ($records AS $record) {
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            switch ($record['Type']) {
                case 0:
                    $record['Type'] = 'Bảo mật cao';
                    break;
                case 1:
                    $record['Type'] = 'Bảo mật thấp';
                    break;
                default :
                    $record['Type'] = '-';
                    break;
            }
            switch ($record['Status']) {
                case 0:
                    $record['Status'] = 'Có thể mượn';
                    break;
                case 1:
                    $record['Status'] = 'Đang cho mượn';
                    break;
                case 2:
                    $record['Status'] = 'Hỏng';
                    break;
                default :
                    $record['Status'] = '-';
                    break;
            }
            $rows[] = array('id' => $record['ItemID'],
                'cell' => $record->toArray()
            );
        }

        $jsonData = array(
            'page' => $page,
            'total' => $total,
            'rows' => $rows
        );
        echo Zend_Json::encode($jsonData);
    }

}
