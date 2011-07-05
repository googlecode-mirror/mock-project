<?php

/**
 * Controller for test
 */
class Front_TestController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
        echo "Test page <br />";
        // setting view
        $this->view->headScript()->appendFile('lib/flexigrid-1.1/js/flexigrid.pack.js');
        $this->view->headLink()->appendStylesheet('lib/flexigrid-1.1/css/flexigrid.pack.css');
    }

    public function recordsAction() {
        $loan = new Front_Model_DbTable_Loan();

        $sort_column = $this->_getParam('sortname', 'Ma_tai_san'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Ma_tai_san');
        $search_for = $this->_getParam('query', '');

        $select = $loan->select()->order("$sort_column $sort_order")->limit($limit, $offset);

        if (!empty($search_column) && !empty($search_for)) {
            $select->where($search_column . ' LIKE ?', '%' . $search_for . '%');
        }

        $pager = Zend_Paginator::factory($select);
        $pager->setCurrentPageNumber($page);
        $pager->setItemCountPerPage($limit);
        $records = $pager->getIterator();
        $total_rows = $loan->fetchAll()->count();

        foreach ($records AS $record) {
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $rows[] = array('id' => $record['Ma_tai_san'],
                'cell' => $record->toArray()
            );
        }

        $jsonData = array(
            'page' => $page,
            'total' => $pager->getTotalItemCount(),
            'rows' => $rows
        );
        echo Zend_Json::encode($jsonData);
    }

}