<?php

class Asset_HistoryController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function detailAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $HistoryID = (int) $this->getRequest()->getPost('HistoryID', -1);
            if ($HistoryID > 0) {
                require_once APPLICATION_PATH . '/modules/asset/models/DbTable/History.php';
                $history = new Asset_Model_DbTable_History();
                $select = $history->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                        ->setIntegrityCheck(false)
                        ->join(array('lu' => 'memberinfor'), 'historyinfor.LUserID = lu.UserID', array('LUsername' => 'lu.Username'))
                        ->join(array('ru' => 'memberinfor'), 'historyinfor.RUserID = ru.UserID', array('RUsername' => 'ru.Username'))
                        ->join('iteminfor', 'historyinfor.ItemID = iteminfor.ItemID', array('Ma_tai_san', 'Ten_tai_san'))
                        ->where("historyinfor.HistoryID='$HistoryID'");
                if ($row = $history->fetchRow($select)) {
                    $status = 'success';
                    echo Zend_Json::encode(array('status' => $status, 'data' => $row->toArray()));
                } else {
                    $status = 'error';
                    $msg = 'Not found detail of this history.';
                    echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                }
            } else {
                $status = 'error';
                $msg = 'Not found detail of this history.';
                echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
            }
        } else {
            $status = 'error';
            $msg = 'Not found POST value.';
            echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
        }
        
    }

    public function listAction() {
        
    }

    public function recordsAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/History.php';
        $history = new Asset_Model_DbTable_History();

        $sort_column = $this->_getParam('sortname', 'Date'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'ItemID');
        $search_for = $this->_getParam('query', '');

//        $select = $history->select()->order("$sort_column $sort_order")->limit($limit, $offset);
        $select = $history->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                        ->setIntegrityCheck(false)
                        ->join(array('lu' => 'memberinfor'), 'historyinfor.LUserID = lu.UserID', array('LUsername' => 'lu.Username'))
                        ->join(array('ru' => 'memberinfor'), 'historyinfor.RUserID = ru.UserID', array('RUsername' => 'ru.Username'))
                        ->join('iteminfor', 'historyinfor.ItemID = iteminfor.ItemID', array('Ma_tai_san', 'Ten_tai_san'))
                        ->order("$sort_column $sort_order")->limit($limit, $offset);

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
            $rows[] = array('id' => $record['HistoryID'],
                'cell' => $record->toArray()
            );
        }

//        $this->getResponse()
//                ->setHeader('Content-Type', 'application/json');

        $jsonData = array(
            'page' => $page,
            'total' => $pager->getTotalItemCount(),
            'rows' => $rows
        );
        echo Zend_Json::encode($jsonData);
    }

}
