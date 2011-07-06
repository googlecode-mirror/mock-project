<?php

/**
 * Controller for test
 */
class Front_TestController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function ingotAction() {
        $books = array(
            array('title' => 'PHP Objects, Patterns, and Practice (2nd edition)',
                'author' => 'Matt Zandstra',
                'purchased' => '2006-05-01'),
            array('title' => 'Patterns of Enterprise Application Architecture',
                'author' => 'Martin Fowler',
                'purchased' => '2007-08-10'),
            array('title' => 'Domain Driven Design: Tackling Complexity in the Heart of Software',
                'author' => 'Eric Evans',
                'purchased' => '2009-02-06')
        );

        $grid = new Ingot_JQuery_JqGrid('bookshelf',
                        new Ingot_JQuery_JqGrid_Adapter_Array($books));

        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('title'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('author'));
        $grid->addColumn(new Ingot_JQuery_JqGrid_Column('purchased'));

        $grid->registerPlugin(new Ingot_JQuery_JqGrid_Plugin_ToolbarFilter());
        $this->view->grid = $grid->render();
    }

    public function indexAction() {
        // action body
        echo "Test page <br />";
        $baseUrl = $this->view->baseUrl();
        // setting view
        $this->view->headScript()->appendFile($baseUrl . '/lib/flexigrid-1.1/js/flexigrid.js');
        $this->view->headLink()->appendStylesheet($baseUrl . '/lib/flexigrid-1.1/css/flexigrid.css');
    }

    public function recordsAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

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

        foreach ($records AS $record) {
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $rows[] = array('id' => $record['Ma_tai_san'],
                'cell' => $record->toArray()
            );
        }
//        header("Content-type: application/json");
        $jsonData = array(
            'page' => $page,
            'total' => $pager->getTotalItemCount(),
            'rows' => $rows
        );
        echo Zend_Json::encode($jsonData);

        // Send headers.
//        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
//        header("Cache-Control: no-cache, must-revalidate");
//        header("Pragma: no-cache");
//        header("Content-type: text/xml");
//        // Prep the XML.
//        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?";
//        $xml .= ">\n";
//        $xml .= "<rows>";
//        $xml .= "<page>$page</page>";
//        $xml .= "<total>" . $pager->getTotalItemCount() . "</total>";
//        foreach ($records as $record) {
//            $xml .= sprintf('<row Ma_tai_san="%d">', $record['Ma_tai_san']);
//            $xml .= sprintf('<cell><![CDATA[%d]]></cell>', $record['Ma_tai_san']);
//            $xml .= sprintf('<cell><![CDATA[%d]]></cell>', $record['UserID']);
//            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $record['Detail']);
//            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $record['Date']);
//            $xml .= sprintf('</row>');
//        }
//        $xml .= "</rows>";
//        $this->getResponse()->setBody($xml);
    }

}