<?php

class Asset_ItemController extends Zend_Controller_Action {

    public function init() {
        
    }

//    public function indexAction() {
//        
//    }

    public function addAction() {

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
        require_once APPLICATION_PATH . '/modules/asset/forms/Item.php';

        $form = new Asset_Form_Item();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $itemID = (int) $form->getValue('ItemID');
                $maTS = $form->getValue('MaTS');
                $tenTS = $form->getValue('TenTS');
                $descr = $form->getValue('Description');
                $type = $form->getValue('Type');
                $startDate = date("m/d/Y", ($form->getValue('StartDate')));
                $price = $form->getValue('Price');
                $warrantyTime = $form->getValue('WarrantyTime');
                $status = $form->getValue('Status');
                $place = $form->getValue('Place');
                $item = new Asset_Model_DbTable_Item();
                $return = $item->addItem($maTS, $tenTS, $descr, $type, $startDate, $price, $warrantyTime, $status, $place);
                switch ($return) {
                    case -1: // loi email da ton tai
//                        break;
                    case -2: // loi user da ton tai
//                        break;
                    case 0: // loi ko update dc
//                        break;
                    default : // update thanh cong
                        $this->_redirect('/asset/item/list');
                        break;
                }
            } else {
                $form->populate($formData);
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
        require_once APPLICATION_PATH . '/modules/asset/forms/Item.php';

        $form = new Asset_Form_Item();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $itemID = (int) $form->getValue('ItemID');
                $maTS = $form->getValue('MaTS');
                $tenTS = $form->getValue('TenTS');
                $descr = $form->getValue('Description');
                $type = $form->getValue('Type');
                $startDate = date("m/d/Y", ($form->getValue('StartDate')));
                $price = $form->getValue('Price');
                $warrantyTime = $form->getValue('WarrantyTime');
                $status = $form->getValue('status');
                $place = $form->getValue('Place');
                $item = new Asset_Model_DbTable_Item();
                $return = $item->editItem(itemID, $maTS, $tenTS, $descr, $type, $startDate, $place, $warrantyTime, $status, $place);
                switch ($return) {
                    case -1: // loi email da ton tai
//                        break;
                    case -2: // loi user da ton tai
//                        break;
                    case 0: // loi ko update dc
//                        break;
                    default : // update thanh cong
                        $this->_redirect('/asset/item/list');
                        break;
                }
            } else {
                $form->populate($formData);
            }
        } else {
            $itemID = (int) $this->_getParam('ItemID', -1);
            if ($itemID > 0) {
                $item = new Asset_Model_DbTable_Item();
                $form->populate($item->getItemFromID($itemID));
            } else {
                // ko ton tai Item
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $ItemID = (int) $this->_getParam('ItemID', -1);
        // TODO : xu ly confirm delete
        if ($ItemID > 0) {
            require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
            $item = new Asset_Model_DbTable_Item();
            $item->deleteItem($ItemID);
            $this->_redirect('/asset/item/list');
        } else {
            // ko ton tai item
        }
    }

    public function detailAction() {

        $ItemID = (int) $this->_getParam('ItemID', -1);
        if ($ItemID > 0) {
            require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
            $item = new Asset_Model_DbTable_Item();
            $this->view->item = $item->getItemFromID($ItemID);
        } else {
            //TODO ko ton tai Item
        }
    }

    public function listAction() {
        $this->view->mode = $this->_getParam('mode', 1);

        // phan chuc nang theo user type
        // Super Admin : add, edit, delete, detail, list
        // Admin : edit, detail, list
        // User, IT : detail, list
        // phan chuc nang theo yeu cau
        // mode = 1 : list all item
        // mode = 2 : list all item free
        // mode = 3 : list all item busy
        // mode = 4 : list all item corrupt
    }

    public function recordsAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
        $items = new Asset_Model_DbTable_Item();

        $sort_column = $this->_getParam('sortname', 'ItemID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Ma_tai_san');
        $search_for = $this->_getParam('query', '');

        $mode = (int) $this->_getParam('mode', 1);
//        $select = $items->select()->order("$sort_column $sort_order")->limit($limit, $offset);
//       echo $mode;
//       exit ();
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

        foreach ($records AS $record) {
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $rows[] = array('id' => $record['ItemID'],
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
