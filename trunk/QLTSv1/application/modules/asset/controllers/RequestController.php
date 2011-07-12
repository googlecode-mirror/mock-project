<?php

class Asset_RequestController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function addAction() { //user gui di
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/asset/forms/request.php';
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
//        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        $form = new Asset_Form_Request();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $RequestID = $form->getValue('RequestID');
                $MaTS = $form->getValue('MaTS');
                $Type = $form->getValue('Type');
                $Date = $form->getValue('Date');
                $Detail = $form->getValue('Detail');

                $request = new Asset_Model_DbTable_Request();
                $item = new Asset_Model_DbTable_Item();

                $itemInfo = $item->getItemFromMa($MaTS);
                if ($itemInfo != NULL) {
                    $request->addRequest(Zend_Auth::getInstance()->getIdentity()->UserID, $MaTS, $Type, $Detail, $Date, 0);
                    $status = 'Success';
                    $msg = 'Request success';
                } else {
                    $status = 'Error';
                    $msg = 'Not found item.';
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

    public function acceptAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';

        if ($this->getRequest()->isPost()) {
            $RequestID = $this->getRequest()->getPost('RequestID', -1);
            if ($RequestID > 0) {
                $re = new Asset_Model_DbTable_Request();
                if ($re->getRequestFromID($RequestID) != NULL) {
                    $re->update(array('Accept' => 1), "RequestID = '$RequestID'");
                    // TODO: cap nhat status cho item, add record vao bang History, add record vao bang Loan
                    $status = 'Success';
                    $msg = 'Updata database success';
                } else {
                    // ko tim thay request
                    $status = 'Error';
                    $msg = 'Not found this request';
                }
            } else {
                // ko ton tai $RequestID
                $status = 'Error';
                $msg = 'POST value format invaild';
            }
        } else {
            // ko co POST
            $status = 'Error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function disacceptAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';

        if ($this->getRequest()->isPost()) {
            $RequestID = $this->getRequest()->getPost('RequestID', -1);
            if ($RequestID > 0) {
                $re = new Asset_Model_DbTable_Request();
                if ($re->getRequestFromID($RequestID) != NULL) {
                    $re->update(array('Accept' => 2), "RequestID = '$RequestID'");
                    $status = 'Success';
                    $msg = 'Updata database success';
                } else {
                    // ko tim thay request
                    $status = 'Error';
                    $msg = 'Not found this request';
                }
            } else {
                // ko ton tai $RequestID
                $status = 'Error';
                $msg = 'POST value format invaild';
            }
        } else {
            // ko co POST
            $status = 'Error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function detailAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
        if ($this->getRequest()->getPost()) {
            $requestid = $this->getRequest()->getPost('RequestID', -1);
            if ($requestid > 0) {
                $re = new Asset_Model_DbTable_Request();
                $select = $re->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                        ->setIntegrityCheck(false)
                        ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                        ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                        ->where("RequestID = '$requestid'");
                $data = $re->fetchRow($select);
                if ($data == NULL) {
                    $status = 'Error';
                    $msg = 'Not found upgrade record.';
                    echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                    exit();
                } else {
                    $status = 'success';
                    echo Zend_Json::encode(array('status' => $status, 'data' => $data->toArray()));
                }
            } else {
                $status = 'Error';
                $msg = 'Not found detail upgrade';
                echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value';
            echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
        }
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
        if ($this->getRequest()->getPost()) {
            $requestid = $this->getRequest()->getPost('RequestID', -1);
            if ($requestid > 0) {
                $re = new Asset_Model_DbTable_Request();
                if ($re->getRequestFromID($requestid) == NULL) {
                    $status = 'Error';
                    $msg = 'Not found request record.';
                    echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                    exit();
                } else {
                    $re->deleteRequest($requestid);
                    $status = 'Success';
                    $msg = 'Update database success';
                }
            } else {
                $status = 'Error';
                $msg = 'Not found detail request';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function listAction() {
        // phan chuc nang theo user type
        // Super Admin, Admin : add, delete, detail, list, accept, disaccept
        // User, IT : detail, list, add
        // phan chuc nang theo yeu cau
        // mode = 1 : list all item
        // mode = 2 : list all item chua accept/disaccept
        // mode = 3 : list all item cua 1 user (default)
        $this->view->mode = $this->_getParam('mode', 3);
        require_once APPLICATION_PATH . '/modules/asset/forms/Request.php';
        $form = new Asset_Form_Request();
        $this->view->form = $form;
    }

    public function recordsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
        $request = new Asset_Model_DbTable_Request();

        $sort_column = $this->_getParam('sortname', 'RequestID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Username');
        $search_for = $this->_getParam('query', '');

        $mode = (int) $this->_getParam('mode', 3);

        switch ($mode) {
            case 1: // list all item
                $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                ->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 2: // list all item chua xu ly
                $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                ->where("requestinfor.Accept = 0")
                                ->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 3: // list all item cua 1 user
            default :
                $uid = Zend_Auth::getInstance()->getIdentity()->UserID;
                $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                ->where("requestinfor.UserID = '$uid'")
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
            $rows[] = array('id' => $record['RequestID'],
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
