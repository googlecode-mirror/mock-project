<?php

class Asset_UpgradeController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function addAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

//        require_once APPLICATION_PATH . '/modules/asset/forms/Upgrade.php';
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Upgrade.php';
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Item.php';
//        require_once APPLICATION_PATH . '/modules/user/models/DbTable/Member.php';
        $form = new Asset_Form_Upgrade();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $UpgradeID = $form->getValue('UpgradeID');
                $MaTS = $form->getValue('MaTS');
                $Username = $form->getValue('Username');
                $Manager = $form->getValue('Manager');
                $Date = $form->getValue('Date');
                $Detail = $form->getValue('Detail');

                $upgrade = new Asset_Model_DbTable_Upgrade();
                $item = new Asset_Model_DbTable_Item();
                $user = new User_Model_DbTable_Member();

                $itemInfo = $item->getItemFromMa($MaTS);
                if ($itemInfo != NULL) {

                    $uname = $user->getMemberFromUsername($Username);
                    if ($uname == NULL) {
                        $status = 'Error';
                        $msg = 'Not found Username.';
                        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                        exit();
                    }
                    $umana = $user->getMemberFromUsername($Manager);
                    if ($umana == NULL) {
                        $status = 'Error';
                        $msg = 'Not found Manager.';
                        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                        exit();
                    }
                    $upgrade->addUpgrade($uname['UserID'], $umana['UserID'], $itemInfo['ItemID'], $Detail, $Date);
                    $status = 'Success';
                    $msg = 'Upgrade success';
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

    public function detailAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Upgrade.php';
        if ($this->getRequest()->getPost()) {
            $upgradeid = $this->getRequest()->getPost('UpgradeID', -1);
            if ($upgradeid > 0) {
                $up = new Asset_Model_DbTable_Upgrade();
                $select = $up->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                        ->setIntegrityCheck(false)
                        ->join(array('u' => 'memberinfor'), 'upgradeinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                        ->join(array('m' => 'memberinfor'), 'upgradeinfor.ManagerID = m.UserID', array('Manager' => 'm.Username'))
                        ->join(array('i' => 'iteminfor'), 'upgradeinfor.ItemID = i.ItemID', array('TenTS' => 'i.Ten_tai_san', 'MaTS' => 'i.Ma_tai_san'))
                        ->where("UpgradeID = '$upgradeid'");
                $data = $up->fetchRow($select);
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
        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Upgrade.php';
        if ($this->getRequest()->getPost()) {
            $upgradeid = $this->getRequest()->getPost('UpgradeID', -1);
            if ($upgradeid > 0) {
                $up = new Asset_Model_DbTable_Upgrade();
                if ($up->getUpgradeFromID($upgradeid) == NULL) {
                    $status = 'Error';
                    $msg = 'Not found upgrade record.';
                    echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
                    exit();
                } else {
                    $up->deleteUpgrade($upgradeid);
                    $status = 'Success';
                    $msg = 'Update database success';
                }
            } else {
                $status = 'Error';
                $msg = 'Not found detail upgrade';
            }
        } else {
            $status = 'Error';
            $msg = 'Not found POST value';
        }
        echo Zend_Json::encode(array('status' => $status, 'msg' => $msg));
    }

    public function listAction() {
        require_once APPLICATION_PATH . '/modules/asset/forms/Upgrade.php';
        $form = new Asset_Form_Upgrade();
        $this->view->form = $form;
    }

    public function recordsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

//        $this->getResponse()
//                ->setHeader('Content-Type', 'application/json');
//        require_once APPLICATION_PATH . '/modules/asset/models/DbTable/Upgrade.php';
        $upgrade = new Asset_Model_DbTable_Upgrade();

        $sort_column = $this->_getParam('sortname', 'UpgradeID'); # this will default to undefined
        $sort_order = $this->_getParam('sortorder', 'desc'); # this will default to undefined
        $page = $this->_getParam('page', 1);
        $limit = $this->_getParam('rp', 10);
        $offset = (($page - 1) * $limit);
        $search_column = $this->_getParam('qtype', 'Ten_tai_san');
        $search_for = $this->_getParam('query', '');

        $uInfo = (array) Zend_Auth::getInstance()->getIdentity();
        if ($uInfo['Role'] == 0 || $uInfo['Role'] == 2) {
            // SuperAdmin or IT
            $select = $upgrade->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                            ->setIntegrityCheck(false)
                            ->join(array('u' => 'memberinfor'), 'upgradeinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                            ->join(array('m' => 'memberinfor'), 'upgradeinfor.ManagerID = m.UserID', array('Manager' => 'm.Username'))
                            ->join(array('i' => 'iteminfor'), 'upgradeinfor.ItemID = i.ItemID', array('TenTS' => 'i.Ten_tai_san', 'MaTS' => 'i.Ma_tai_san'))
                            ->order("$sort_column $sort_order")->limit($limit, $offset);
        } else {
            $select = $upgrade->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
                            ->setIntegrityCheck(false)
                            ->join(array('u' => 'memberinfor'), 'upgradeinfor.UserID = u.UserID', array('Username' => 'u.Username'))
                            ->join(array('m' => 'memberinfor'), 'upgradeinfor.ManagerID = m.UserID', array('Manager' => 'm.Username'))
                            ->join(array('i' => 'iteminfor'), 'upgradeinfor.ItemID = i.ItemID', array('TenTS' => 'i.Ten_tai_san', 'MaTS' => 'i.Ma_tai_san'))
                            ->where("u.Username = '" . $uInfo['Username'] . "'")
                            ->order("$sort_column $sort_order")->limit($limit, $offset);
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
            $rows[] = array('id' => $record['UpgradeID'],
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
