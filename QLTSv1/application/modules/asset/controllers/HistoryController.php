<?php

class Asset_HistoryController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function detailAction() {
        $HistoryID = (int) $this->_getParam('ID', -1);
        if ($ItemID > 0) {
            require_once APPLICATION_PATH . '/modules/asset/models/DbTable/History.php';
            $history = Asset_Model_DbTable_History();
            $this->view->history = $history->getHistoryFromID($HistoryID);
        } else {
            //TODO ko ton tai Item
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

        $select = $history->select()->order("$sort_column $sort_order")->limit($limit, $offset);

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
