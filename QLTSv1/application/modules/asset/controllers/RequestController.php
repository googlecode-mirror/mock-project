<?php

class Asset_RequestController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function addAction() { //user gui di
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

//        require_once APPLICATION_PATH . '/modules/asset/forms/request.php';
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
//        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        $form = new Asset_Form_Request();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $MaTS = $form->getValue('MaTS');
                $Type = (int) $form->getValue('Type');
                $Date = date('Y-m-d');
                $Detail = $form->getValue('Detail');

                $request = new Asset_Model_DbTable_Request();
                $item = new Asset_Model_DbTable_Item();
                $uInfo = (array) Zend_Auth::getInstance()->getIdentity();
                $itemInfo = $item->getItemFromMa($MaTS);
                if ($itemInfo != NULL) {
                    if ($Type == 1) {
                        $loan = new Asset_Model_DbTable_Loan();
                        $loInfo = $loan->getLoanFromMa($MaTS);
                        if ($loInfo != NULL && $loInfo['UserID'] == $uInfo['UserID']) {
                            if ($uInfo['Role'] == 2) {
                                $status = 'Error';
                                $msg = 'You cannot request upgrade item.';
                                echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                                exit();
                            }
                            $request->addRequest($uInfo['UserID'], $MaTS, $Type, $Detail, $Date, '0');
                            $status = 'Success';
                            $msg = 'Request success';
                        } else {
                            $status = 'Error';
                            $msg = 'Bạn không thể yêu cầu nâng cấp tài sản bạn đang không sở hữu.';
                        }
                    } else {
                        if ($itemInfo['Status'] != 2) {
                            $request->addRequest($uInfo['UserID'], $MaTS, $Type, $Detail, $Date, '0');
                            $status = 'Success';
                            $msg = 'Request success';
                        } else {
                            $status = 'Error';
                            $msg = 'Tài sản đang hỏng.';
                        }
                    }
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
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';

        if ($this->getRequest()->isPost()) {
            $RequestID = (int) $this->getRequest()->getPost('RequestID', -1);
            if ($RequestID > 0) {
                $re = new Asset_Model_DbTable_Request();
                $reInfor = $re->getRequestFromID($RequestID);
                if ($reInfor != NULL) {
                    $uInfo = (array) Zend_Auth::getInstance()->getIdentity();
                    if ($uInfo['UserID'] == $reInfor['UserID']) {
                        $status = 'Error';
                        $msg = 'You cannot accept your request.';
                        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                        exit();
                    }
                    if ($reInfor['Type'] == 0) {
                        $re->deleteRequest($RequestID);
                        $me = new Asset_Model_DbTable_Message();
                        $mTitle = 'Thông báo: Yêu cầu mượn được chấp nhận';
                        $mDetail = 'Yêu cầu mượn tài sản ' . $reInfor['Ma_tai_san'] . ' đã được chấp nhận. Bạn hãy gặp tôi sơm nhất để nhận tài sản';
                        $me->addMessage($uInfo['UserID'], $reInfor['UserID'], $mTitle, $mDetail);
                    } else {

                        $re->update(array('Accept' => 1), "RequestID = '$RequestID'");
                        $me = new Asset_Model_DbTable_Message();
                        $mTitle = 'Thông báo: Yêu cầu nâng cấp được chấp nhận';
                        $mDetail = 'Yêu cầu nâng cấp tài sản ' . $reInfor['Ma_tai_san'] . ' đã được chấp nhận. Bạn hãy gặp tôi sơm nhất để được nâng cấp tài sản';
                        $me->addMessage($uInfo['UserID'], $reInfor['UserID'], $mTitle, $mDetail);
                    }
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
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';

        if ($this->getRequest()->isPost()) {
            $RequestID = $this->getRequest()->getPost('RequestID', -1);
            if ($RequestID > 0) {
                $re = new Asset_Model_DbTable_Request();
                $reInfor = $re->getRequestFromID($RequestID);
                $uInfo = (array) Zend_Auth::getInstance()->getIdentity();
                if ($reInfor != NULL) {
                    if ($uInfo['UserID'] == $reInfor['UserID']) {
                        $status = 'Error';
                        $msg = 'You cannot denied your request.';
                        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                        exit();
                    }
                    if ($reInfor['Type'] == 0) {
                        $re->deleteRequest($RequestID);
                        $me = new Asset_Model_DbTable_Message();
                        $mTitle = 'Thông báo: Yêu cầu mượn bị từ chối';
                        $mDetail = 'Yêu cầu mượn tài sản ' . $reInfor['Ma_tai_san'] . ' bị từ chối. Bạn có thể mượn tà sản khác hoặc mượn vào lúc khác';
                        $me->addMessage($uInfo['UserID'], $reInfor['UserID'], $mTitle, $mDetail);
                    } else {

                        $re->deleteRequest($RequestID);
                        $me = new Asset_Model_DbTable_Message();
                        $mTitle = 'Thông báo: Yêu cầu nâng cấp bị từ chối';
                        $mDetail = 'Yêu cầu nâng cấp tài sản ' . $reInfor['Ma_tai_san'] . ' bị từ chối. Bạn có thể mượn tà sản khác hoặc yêu cầu nâng cấp lúc khác';
                        $me->addMessage($uInfo['UserID'], $reInfor['UserID'], $mTitle, $mDetail);
                    }
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
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
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
                    $reInfor = $data->toArray();
                    if ($reInfor['Type'] == 1) {
                        $reInfor['Type'] = 'Yêu cầu nâng cấp';
                    } else {
                        $reInfor['Type'] = 'Yêu cầu mượn';
                    }
                    if ($reInfor['Accept'] == '1') {
                        $reInfor['Accept'] = 'Đang xử lý';
                    } else {
                        $reInfor['Accept'] = 'Chưa xử lý';
                    }
                    echo Zend_Json::encode(array('status' => $status, 'data' => $reInfor));
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
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
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

    public function successAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
        if ($this->getRequest()->getPost()) {
            $requestid = $this->getRequest()->getPost('RequestID', -1);
            if ($requestid > 0) {
                $re = new Asset_Model_DbTable_Request();
                $reInfo = $re->getRequestFromID($requestid);
                if ($reInfo == NULL) {
                    $status = 'Error';
                    $msg = 'Not found request record.';
                    echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                    exit();
                } elseif ($reInfo['Accept'] == 1) {
                    $re->deleteRequest($requestid);
                    $status = 'Success';
                    $msg = 'Update database success';
                } else {
                    $status = 'Error';
                    $msg = 'Yêu cầu chưa được xử lý.';
                }
            } else {
                $status = 'Error';
                $msg = 'Not found request record.';
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
//        $this->view->mode = $this->_getParam('mode', 3);
        require_once APPLICATION_PATH . '/modules/asset/forms/Request.php';
        $form = new Asset_Form_Request();
        $this->view->form = $form;
    }

    public function recordsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()
                ->setHeader('Content-Type', 'application/json');
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Request.php';
        $request = new Asset_Model_DbTable_Request();

        $sort_column = $this->_getParam('sortname', 'RequestID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Username');
        $search_for = $this->_getParam('query', '');

        $mode = (int) $this->_getParam('mode', 4);
        $uid = Zend_Auth::getInstance()->getIdentity()->UserID;
        $uRole = (int) Zend_Auth::getInstance()->getIdentity()->Role;

        switch ($mode) {
            case 1: // list all item
                if ($uRole != 0) {
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.UserID = '$uid'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                } else {
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                }

                break;
            case 2: // list all item chua xu ly
                if ($uRole == 1) {
                    // admin
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.Type = '0' AND requestinfor.Accept = '0'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                } elseif ($uRole == 2) {
                    // IT
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.Type = '1' AND requestinfor.Accept = '0'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                } elseif ($uRole == 0) {
                    // superadmin
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.Accept = '0'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                } else {
                    // user
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.UserID='$uid' AND requestinfor.Accept = '0'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                }
                break;
            case 3: // list all item dang xu ly
                if ($uRole == 1) {
                    // admin : ko co request muon dang xu ly
                    echo Zend_Json::encode(array('page' => $page, 'total' => 0, 'rows' => NULL));
                    exit();
                } elseif ($uRole == 2) {
                    // IT
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.Type = '1' AND requestinfor.Accept = '1'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                } elseif ($uRole == 0) {
                    // superadmin
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.Accept = '1'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                } else {
                    // user
                    $select = $request->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                    ->setIntegrityCheck(false)
                                    ->join(array('u' => 'memberinfor'), 'requestinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                                    ->join(array('i' => 'iteminfor'), 'requestinfor.Ma_tai_san = i.Ma_tai_san', array('TenTS' => 'i.Ten_tai_san', 'Status' => 'i.Status', 'ItemID' => 'i.ItemID'))
                                    ->where("requestinfor.UserID='$uid' AND requestinfor.Accept = '1'")
                                    ->order("$sort_column $sort_order")->limit($limit, $offset);
                }
                break;
            case 4:
            default : // list all item cua user
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
        $total = $pager->getTotalItemCount();
        if ($total == 0) {
            echo Zend_Json::encode(array('page' => $page, 'total' => $total, 'rows' => NULL));
            exit();
        }
        foreach ($records AS $record) {
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            if (isset($record['Type']) && isset($record['Accept'])) {
                switch ($record['Type']) {
                    case 0:
                        $record['Type'] = 'Yêu cầu mượn';
                        break;
                    case 1:
                        $record['Type'] = 'Yêu cầu nâng cấp';
                        break;
                    default :
                        $record['Type'] = 'Yêu cầu mượn';
                        break;
                }
                switch ($record['Accept']) {
                    case 0:
                        $record['Accept'] = 'Yêu cầu chưa xử lý';
                        break;
                    case 1:
                    default :
                        $record['Accept'] = 'Yêu cầu đang xử lý';
                        break;
                }
            }

            $rows[] = array('id' => $record['RequestID'],
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
