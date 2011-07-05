<?php

class Front_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
        echo "Hello world <br />";
        // setting view
        $this->view->headScript()->appendFile('lib/flexigrid-1.1/js/flexigrid.pack.js');
        $this->view->headLink()->appendStylesheet('lib/flexigrid-1.1/css/flexigrid.pack.css');

    }

    public function recordsAction() {

        $item = new Front_Model_DbTable_Item();
        $total_rows = $item->fetchAll()->count();
        $sort_column = $this->_getParam('sortname', 'ItemID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype');
        $search_for = $this->_getParam('query');

        $Select = $item->select()->order("$sort_column $sort_order")->limit($limit, $offset);

        if (!empty($search_column) && !empty($search_for)) {
            $Select->where($search_column . ' LIKE ?', '%' . $search_for . '%');
        }

        $Pager = Zend_Paginator::factory($Select);
        $Pager->setCurrentPageNumber($page);
        $Pager->setItemCountPerPage($limit);
        $rows = $Pager->getIterator();
////        Zend_Debug::dump($rows);
////        exit ();
//        $arrayData = array(
//            'page' => $page,
//            'total' => $total_rows,
//            'rows' => array()
//        );
//        
//        foreach($rows AS $row){
//        //If cell's elements have named keys, they must match column names
//        //Only cell's with named keys and matching columns are order independent.
//        $entry = array('id'=>$row['ItemID'],
//                'cell'=>array(
//                        'ItemID'=>$row['ItemID'],
//                        'Ma_tai_san'=>$row['Ma_tai_san'],
//                        'printable_name'=>$row['printable_name'],
//                        'iso3'=>$row['iso3'],
//                        'numcode'=>$row['numcode']
//                ),
//        );
//        $jsonData['rows'][] = $entry;
//}

     
        // Send headers.
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: text/xml");

        // Prep the XML.
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?";
        $xml .= ">\n";
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$total_rows</total>";
        foreach ($rows as $row) {
            $xml .= sprintf('<row id="%s">', $row['ItemID']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['ItemID']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['Ma_tai_san']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['Ten_tai_san']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['Description']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['Type']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['StartDate']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['Price']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['WarrantyTime']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['Status']);
            $xml .= sprintf('<cell><![CDATA[%s]]></cell>', $row['Place']);
            $xml .= '</row>';
        }
        $xml .= '</rows>';

        // Disable the default layout and output the XML.
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $this->getResponse()->setBody($xml);
    }

}
