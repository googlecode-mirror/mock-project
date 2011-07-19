<?php

class Asset_MessageController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function addAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $from = Zend_Auth::getInstance()->getIdentity()->UserID;
            $touser = $this->getRequest()->getPost('To');
            $title = $this->getRequest()->getPost('Title');
            $detail = $this->getRequest()->getPost('Detail');

//            require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
            $us = new User_Model_DbTable_Member();
            if ($us->getMemberFromUsername($touser) == NULL) {
                $toUserInfo = $us->getMemberFromUsername($touser);
                // ko ton tai to use
                $status = 'Error';
                $msg = 'Not found to member';
                echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                exit();
            }
            $toUserInfo = $us->getMemberFromUsername($touser);
            $to = $toUserInfo['UserID'];
            $me = new Asset_Model_DbTable_Message();
            if ($me->addMessage($from, $to, $title, $detail)) {
                // success
                $status = 'Success';
                $msg = 'Create success';
            } else {
                // fail
                $status = 'Error';
                $msg = 'Not create new message.';
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
        if ($this->getRequest()->getPost()) {
            $MessageID = $this->getRequest()->getPost('MessageID', -1);
            if ($MessageID > 0) {
                $me = new Asset_Model_DbTable_Message();
                if ($me->getMessage($MessageID) == NULL) {
                    $status = 'Error';
                    $msg = 'Not found message.';
                    echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                    exit();
                } else {
                    $me->deleteMessage($MessageID);
                    $status = 'Success';
                    $msg = 'Update database success';
                }
            } else {
                $status = 'Error';
                $msg = 'Not found message';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function readAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->getRequest()->isPost()) {
            $MessageID = (int) $this->getRequest()->getPost('MessageID', -1);
            $uid = Zend_Auth::getInstance()->getIdentity()->UserID;
            if ($MessageID > 0) {
                $message = new Asset_Model_DbTable_Message();
                $select = $message->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                        ->setIntegrityCheck(false)
                        ->join(array('su' => 'memberinfor'), "su.UserID=SendID", array('SUsername' => 'su.Username'))
                        ->join(array('ru' => 'memberinfor'), "ru.UserID=ReceiveID", array('RUsername' => 'ru.Username'))
                        ->where("SendID = '$uid' OR ReceiveID = '$uid'")
                        ->where("MessageID = '$MessageID'");
                if ($row = $message->fetchRow($select)) {
                    $status = 'success';
                    $messageData = $row->toArray();
                    $message->editMessage($MessageID, $messageData['Title'], $messageData['Detail'], 1);
                    echo Zend_Json::encode(array('status' => $status, 'data' => $messageData));
                } else {
                    $status = 'error';
                    $msg = 'Not found detail of this message.';
                    echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                }
            } else {
                $status = 'error';
                $msg = 'Not found detail of this message.';
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

//        $this->getResponse()
//                ->setHeader('Content-Type', 'application/json');

        $message = new Asset_Model_DbTable_Message();
        $userInfo = (array) Zend_Auth::getInstance()->getIdentity();
        $uid = $userInfo['UserID'];

        $sort_column = $this->_getParam('sortname', 'MessageID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Title');
        $search_for = $this->_getParam('query', '');

        $mode = (int) $this->_getParam('mode', 1);

        switch ($mode) {
            case 1: // all message to me
                $select = $message->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('su' => 'memberinfor'), "su.UserID=SendID", array('SUsername' => 'su.Username'))
                                ->join(array('ru' => 'memberinfor'), "ru.UserID=ReceiveID", array('RUsername' => 'ru.Username'))
                                ->where("ReceiveID = '$uid'")->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 2: // all message from me
                $select = $message->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('su' => 'memberinfor'), "su.UserID=SendID", array('SUsername' => 'su.Username'))
                                ->join(array('ru' => 'memberinfor'), "ru.UserID=ReceiveID", array('RUsername' => 'ru.Username'))
                                ->where("SendID = '$uid'")->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 3: // all message to or from me
                $select = $message->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('su' => 'memberinfor'), "su.UserID=SendID", array('SUsername' => 'su.Username'))
                                ->join(array('ru' => 'memberinfor'), "ru.UserID=ReceiveID", array('RUsername' => 'ru.Username'))
                                ->where("SendID = '$uid' OR ReceiveID = '$uid'")->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 4: // all message (Role: admin)
                if ($userInfo['Role'] != 0) {
                    echo Zend_Json::encode(array('page' => $page, 'total' => 0, 'rows' => NULL));
                    exit();
                }
                $select = $message->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('su' => 'memberinfor'), "su.UserID=SendID", array('SUsername' => 'su.Username'))
                                ->join(array('ru' => 'memberinfor'), "ru.UserID=ReceiveID", array('ReceiveUsername' => 'ru.Username'))
                                ->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            case 5:
                $select = $message->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('su' => 'memberinfor'), "su.UserID=SendID", array('SUsername' => 'su.Username'))
                                ->join(array('ru' => 'memberinfor'), "ru.UserID=ReceiveID", array('RUsername' => 'ru.Username'))
                                ->where("ReceiveID = '$uid'")->order("$sort_column $sort_order")->limit($limit, $offset);
                break;
            default :
                $select = $message->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                                ->setIntegrityCheck(false)
                                ->join(array('su' => 'memberinfor'), "su.UserID=SendID", array('SUsername' => 'su.Username'))
                                ->join(array('ru' => 'memberinfor'), "ru.UserID=ReceiveID", array('RUsername' => 'ru.Username'))
                                ->where("ReceiveID = '$uid'")->order("$sort_column $sort_order")->limit($limit, $offset);
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
            $rows[] = array('id' => $record['MessageID'],
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