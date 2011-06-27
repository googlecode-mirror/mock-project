<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoload() {

        $modelLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => 'Front_',
                    'basePath' => APPLICATION_PATH . '/modules/front'));

        return $modelLoader;
    }

    protected function _initDb() {

        $dbOption = $this->getOption('database');
        $db = Zend_Db::factory($dbOption['adapter'], $dbOption['params']);
        $charset = $dbOption['charset'];

        $db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $db->query("SET NAMES ?", array($charset));
        $db->query("SET CHARACTER SET ?", array($charset));

        Zend_Registry::set('connectDB', $db);
        Zend_Db_Table::setDefaultAdapter($db);

        return $db;
    }

    protected function _initSession() {

        Zend_Session::start();
    }

//    protected function _initPlugins() {
//        $front = Zend_Controller_Front::getInstance();
//        $front->registerPlugin(new Default_Plugin_AccessControl(new Default_Model_UserAcl()));
//        return $front;
//    }
//
//    protected function _initLocale() {
//
//        $session = new Zend_Session_Namespace('lang.l10n');
//
//        if ($session->locale) {
//            $locale = new Zend_Locale($session->locale);
//        } else {
//            $locale = new Zend_Locale('vi');
//        }
//
//        $registry = Zend_Registry::getInstance();
//        $registry->set('Zend_Locale', $locale);
//    }
//
//    protected function _initTranslate() {
//
//        $translate = new Zend_Translate('Ini', APPLICATION_PATH . '/languages/', NULL,
//                        array(
//                            'scan' => Zend_Translate::LOCALE_DIRECTORY,
//                            'disableNotices' => 1));
//
//        Zend_Validate::setDefaultTranslator($translate);
//        Zend_Form::setDefaultTranslator($translate);
//        Zend_Dojo_Form::setDefaultTranslator($translate);
//        $registry = Zend_Registry::getInstance();
//        $registry->set('Zend_Translate', $translate);
//    }

    protected function _initView() {

        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();

        $view->setEncoding('UTF-8');
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8')
                ->appendHttpEquiv('Content-Language', 'vi-VN');
        
        $view->headLink(array('rel' => 'shortcut icon', 'href' => 'favicon.ico', 'type' => 'image/x-icon'), 'append');
        
//        $view->headLink()->appendStylesheet($view->baseUrl('/css/style.css', 'screen'));
//        $view->headLink()->appendStylesheet($view->baseUrl('/css/style-ie.css', 'all', 'lte IE 8'));// fix css for IE8 or LOWER
//        // Script
//        $view->headScript()->appendFile($baseUrl . '/js/');
//        // View Helper
//        $view->addHelperPath("Zend/View/Helper/", "Zend_View_Helper");
//        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
//        $view->addHelperPath("Ext/View/Helper", "Ext_View_Helper");
//        $view->addHelperPath("Zend/Dojo/View/Helper", "Zend_Dojo_View_Helper");
//        Zend_Controller_Action_HelperBroker::addHelper(new Zend_Controller_Action_Helper_ViewRenderer($view));
        
        return $view;
    }

}

