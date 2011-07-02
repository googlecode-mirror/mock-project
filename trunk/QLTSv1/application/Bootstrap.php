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
     * function _initSession()
     * 
     * @todo Initialization session and strating session
     * @param null
     * @return null
     */
    protected function _initSession() {

        Zend_Session::start();
    }

//    /**
//     * function _initPlugin()
//     * 
//     * @todo Initialization controller plugin for application
//     * @param null
//     * @return Zend_Controller_Front
//     */
//    protected function _initPlugin() {
//
//        $front = Zend_Controller_Front::getInstance();
//
//        /**
//         * @see ZendExt_Controller_Plugin_AccessControl
//         */
//        include_once 'ZendExt/Controller/Plugin/AccessControl.php';
//
//        $front->registerPlugin(new ZendExt_Controller_Plugin_AccessControl());
//
//        return $front;
//    }

//    /**
//     * function _initLocale()
//     * 
//     * @todo get info locale of user
//     * @param null
//     * @return null
//     */
//    protected function _initLocale() {
//        $session = new Zend_Session_Namespace('lang.l10n');
//        if ($session->locale) {
//            $locale = new Zend_Locale($session->locale);
//        } else {
//            $locale = new Zend_Locale('vi');
//        }
//        $registry = Zend_Registry::getInstance();
//        $registry->set('Zend_Locale', $locale);
//    }
//
//    /**
//     * function _initTranslate()
//     * 
//     * @todo Initialization translate for application with locale
//     * @param null
//     * @return null
//     */
//    protected function _initTranslate() {
//        $translate = new Zend_Translate('Ini', APPLICATION_PATH . '/languages/',
//                        NULL,
//                        array(
//                            'scan' => Zend_Translate::LOCALE_DIRECTORY,
//                            'disableNotices' => 1));
//        Zend_Validate::setDefaultTranslator($translate);
//        Zend_Form::setDefaultTranslator($translate);
//        //Zend_Dojo_Form::setDefaultTranslator($translator);
//        $registry = Zend_Registry::getInstance();
//        $registry->set('Zend_Translate', $translate);
//    }

    /**
     * function _iniView()
     * 
     * @todo Initialization config for view and layout
     * @param null
     * @return Zend_View
     */
//    protected function _iniView() {
//
//        // Get layout and view with config from application.ini
//        $this->bootstrap('layout');
//        $layout = $this->getResource('layout');
//        $view = $layout->getView();
//        Zend_Dojo::enableView($view);
//        $view->doctype('HTML5');
//        Zend_Debug::dump($view);
//        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
//        $view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
//        Zend_Controller_Action_HelperBroker::addHelper(new Zend_Controller_Action_Helper_ViewRenderer($view));
//        return $view;
//    }

}