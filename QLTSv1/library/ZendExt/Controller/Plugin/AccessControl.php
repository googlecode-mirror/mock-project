<?php

/**
 * Class ZendExt_Controller_Plugin_AccessControl
 * 
 * @package ZendExt
 * @version 1.0
 * @author OanhNN
 * 
 * @todo Access control
 */
class ZendExt_Controller_Plugin_AccessControl extends Zend_Controller_Plugin_Abstract {

    /**
     * @var ZendExt_Acl
     */
    private $_acl = NULL;
    /**
     * @var Zend_Auth
     */
    private $_auth = NULL;

    /**
     * function __construct()
     * 
     * @todo construct class
     * @param null
     * @return null
     */
    public function __construct() {

        /**
         * @see ZendExt_Acl
         */
        require_once 'ZendExt/Acl.php';
        // get info of current user
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new ZendExt_Acl();
    }

    /**
     * function preDispatch()
     * 
     * @todo Control request access
     * @param Zend_Controller_Request_Abstract $request
     * @return null
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        // get info of request
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        // get role of current user
//        if ($this->_auth->hasIdentity()) {
//            // logined
//            $identity = $this->_auth->getIdentity();
//            switch ($identity->Role) {
//                case 0: // Super Admin
//                    $role = "SuperAdmin";
//                    break;
//                case 1: // Admin
//                    $role = "Admin";
//                    break;
//                case 2: // IT
//                    $role = "IT";
//                    break;
//                default : // User
//                    $role = "User";
//                    break;
//            }
//            if (!$this->_acl->isAllowed($role, $module . ':' . $controller, $action)) {
////                // Not allowed access
////                $request->setModuleName('front')
////                        ->setControllerName('auth')
////                        ->setActionName('login');
//            } else {
//                // Allowed access
//            }
//        } else {
//            // not login
//            $role = NULL;
//            $request->setModuleName('front')
//                    ->setControllerName('auth')
//                    ->setActionName('login');
//        }
    }

}
