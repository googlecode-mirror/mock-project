<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoload() {

        $defaultModuleAutoloader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => 'Front_',
                    'basePath' => APPLICATION_PATH . '/modules/front'));

        return $defaultModuleAutoloader;
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

    protected function _initMail() {

        $mailServerOptions = $this->getOption('mailserver');
        $smtpHost = $mailServerOptions['host'];
        unset($mailServerOptions['host']);

        $mailTransport = new Zend_Mail_Transport_Smtp($smtpHost, $mailServerOptions);
        Zend_Mail::setDefaultTransport($mailTransport);
        Zend_Mail::getDefaultFrom($mailServerOptions['username'], 'Web system');
//        Zend_Mail::setDefaultReplyTo($mailServerOptions['username'],'Web system');
    }

    protected function _initPlugins() {
        $front = Zend_Controller_Front::getInstance();
        
        require_once 'ZendExt/Controller/Plugin/AccessControl.php';
        require_once 'ZendExt/Acl.php';
        
        $front->registerPlugin(new ZendExt_Controller_Plugin_AccessControl(new ZendExt_Acl()));
        return $front;
    }

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
//
//    protected function _initView() {
//
//        $this->bootstrap('layout');
//        $layout = $this->getResource('layout');
//        $view = $layout->getView();
//
//        $view->setEncoding('UTF-8');
//        $view->doctype('XHTML1_STRICT');
//        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8')
//                ->appendHttpEquiv('Content-Language', 'vi-VN');
//        
//        $view->headLink(array('rel' => 'shortcut icon', 'href' => 'favicon.ico', 'type' => 'image/x-icon'), 'append');
//        
//        
//        return $view;
//    }
}

