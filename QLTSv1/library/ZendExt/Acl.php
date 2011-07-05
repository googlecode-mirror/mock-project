<?php

/**
 * Class ZendExt_Acl
 * 
 * @package ZendExt
 * @version 1.0
 * @author OanhNN
 * 
 * @todo Access control list
 */
class ZendExt_Acl extends Zend_Acl {

    /**
     * function __construct()
     * 
     * @todo construct class
     * @param null
     * @return null
     */
    public function __construct() {
        $this->acl();
    }

    /**
     * function acl()
     * 
     * @todo access control with role of user
     * @param null
     * @return null
     */
    public function acl() {
        
        // User type
        $this->addRole(new Zend_Acl_Role(NULL));
        $this->addRole(new Zend_Acl_Role('IT'), NULL);
        $this->addRole(new Zend_Acl_Role('User'), NULL);
        $this->addRole(new Zend_Acl_Role('Admin'), NULL);
        $this->addRole(new Zend_Acl_Role('SuperAdmin'), NULL);
        
        // module front
        $this->add(new Zend_Acl_Resource('front'))
                ->add(new Zend_Acl_Resource('front:index'), 'front')
                ->add(new Zend_Acl_Resource('front:auth'), 'front')
                ->add(new Zend_Acl_Resource('front:error'), 'front')
                ->add(new Zend_Acl_Resource('front:mail'), 'front');
        
        // module user
        $this->add(new Zend_Acl_Resource('user'))
                ->add(new Zend_Acl_Resource('user:index'), 'user')
                ->add(new Zend_Acl_Resource('user:profile'), 'user')
                ->add(new Zend_Acl_Resource('user:user'), 'user');
        
        // module asset
        $this->add(new Zend_Acl_Resource('asset'))
                ->add(new Zend_Acl_Resource('asset:history'), 'asset')
                ->add(new Zend_Acl_Resource('admin:item'), 'asset')
                ->add(new Zend_Acl_Resource('admin:loan'), 'asset')
                ->add(new Zend_Acl_Resource('admin:request'), 'asset')
                ->add(new Zend_Acl_Resource('admin:upgrade'), 'asset');
        
//        $this->allow('users', 'user:login', array('success', 'logout'))
//                ->allow('users', 'default:comment', array('add', 'delete'))
//                ->allow('users', 'user:profile', array('index', 'edit', 'repassword', 'chpassword'))
//                ->deny('users', 'user:login', 'login')
//                ->deny('users', 'admin:index', 'index')
//                ->deny('users', 'admin');
        // Permission of NULL user
        $this->allow(NULL, 'front:index', array('index'))
            ->allow(NULL, 'front:auth', array('login', 'logout', 'success', 'nopermission'))
            ->allow(NULL, 'front:error', 'error')
            ->allow(NULL, 'front:mail', 'index');
        
//        // Permission of IT user
//        $this->allow('IT', 'front:index', 'index')
//            ->allow('IT', 'front:auth', array('success', 'nopermission'));
//        
//        // Permission of User user
//        $this->allow('User', 'front:index', 'index')
//                ->allow('User', 'front', $privileges);
    }

}
