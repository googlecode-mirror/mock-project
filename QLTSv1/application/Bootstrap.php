<?php

/**
 * QLTS v1
 * 
 * @version 1.0
 * @license
 */

/**
 * Class Bootstrap
 * 
 * @package Application
 * @version 1.0
 * @author OanhNN
 * 
 * @todo Initialization application
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * function _initAutoload()
     * 
     * @todo Initialization default module loader
     * @param null
     * @return Zend_Application_Module_Autholoader
     */
    protected function _initAutoload() {

        $defaultModuleLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => 'Front_',
                    'basePath' => APPLICATION_PATH . '/modules/front'));

        return $defaultModuleLoader;
    }

    /**
     * function _initDb()
     * 
     * @todo Initialization database connect form database config file
     * @param null
     * @return Zend_Db
     */
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

    /**
     * function _initSession()
     * 
     * @todo Initialization session and strating session
     * @param null
     * @return null
     */
    protected function _initSession() {

        Zend_Session::start();
    }

    /**
     * function _initSmtpMailServer()
     * 
     * @todo Initialization SMTP mail server from mail server config file
     * @param null
     * @return null
     */
    protected function _initSmtpMailServer() {

        $mailOptions = $this->getOption('mailserver');

        $mailTransport = new Zend_Mail_Transport_Smtp($mailOptions['host'], $mailOptions['params']);
        Zend_Mail::setDefaultTransport($mailTransport);
        Zend_Mail::setDefaultFrom($mailOptions['params']['username'], 'webmaster');

        unset($mailOptions);
    }

    /**
     * function _initPlugin()
     * 
     * @todo Initialization controller plugin for application
     * @param null
     * @return Zend_Controller_Front
     */
    protected function _initPlugin() {

        /**
         * @see ZendExt_Acl
         * @see ZendExt_Controller_Plugin_AccessControl
         */
        include_once 'ZendExt/Acl.php';
        include_once 'ZendExt/Controller/Plugin/AccessControl.php';

        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new ZendExt_Controller_Plugin_AccessControl(new ZendExt_Acl()));
        return $front;
    }

}


