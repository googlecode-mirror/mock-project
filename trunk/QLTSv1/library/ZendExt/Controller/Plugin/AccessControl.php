<?php

require_once 'ZendExt/Acl.php';

class ZendExt_Controller_Plugin_AccessControl extends Zend_Controller_Plugin_Abstract {

    private $_acl = NULL;
    private $_auth = NULL;

    public function __construct() {
        // get info of current user
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new ZendExt_Acl();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        // get info of request
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        // get role of current user
        $identity = $this->_auth->getIdentity();
        switch ($identity->Role) {
            case 0: // Super Admin
                $role = "superadmin";
                break;
            case 1: // Admin
                $role = "admin";
                break;
            case 2: // IT
                $role = "it";
                break;
            default : // User
                $role = "user";
                break;
        }
        $role = $identity->Role;

//        if (!$this->_acl->isAllowed($role, $module . ':' . $controller, $action)) {
//            // Not allowed access
//            $request->setModuleName('front')
//                    ->setControllerName('auth')
//                    ->setActionName('login');
//        } else {
//            // Allowed access
//            
//        }
    }

}
