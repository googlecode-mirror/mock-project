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
        $this->addRole(new Zend_Acl_Role('Admin'), "User");
        $this->addRole(new Zend_Acl_Role('SuperAdmin'), "Admin");

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
                ->add(new Zend_Acl_Resource('asset:item'), 'asset')
                ->add(new Zend_Acl_Resource('asset:loan'), 'asset')
                ->add(new Zend_Acl_Resource('asset:message'), 'asset')
                ->add(new Zend_Acl_Resource('asset:request'), 'asset')
                ->add(new Zend_Acl_Resource('asset:upgrade'), 'asset');

        // Permission of NULL user
        $this->allow(NULL, 'front:index', array('about'))
                ->allow(NULL, 'front:auth', array('login', 'logout', 'success', 'nopermission'))
                ->allow(NULL, 'front:error', 'error')
                ->allow(NULL, 'front:mail', array('index', 'send'));

        // Permission of IT user
        $this->allow('IT', 'front:index', 'index')
                ->allow('IT', 'front:mail', array('index'))
                ->allow('IT', 'user:profile', array('detail', 'edit'))
                ->allow('IT', 'asset:history', array('list', 'records', 'detail'))
                ->allow('IT', 'asset:item', array('list', 'records', 'detail'))
                ->allow('IT', 'asset:loan', array('list', 'records'))
                ->allow('IT', 'asset:message', array('list', 'records', 'add', 'delete', 'read'))
                ->allow('IT', 'asset:request', array('list', 'records', 'add', 'delete', 'accept', 'detail', 'disaccept', 'success'))
                ->allow('IT', 'asset:upgrade', array('list', 'records', 'detail', 'add', 'delete'));

        // Permission of User user
        $this->allow('User', 'front:index', 'index')
                ->allow('User', 'front:mail', array('index'))
                ->allow('User', 'user:profile', array('detail', 'edit'))
                ->allow('User', 'asset:history', array('list', 'records', 'detail'))
                ->allow('User', 'asset:item', array('list', 'records', 'detail'))
                ->allow('User', 'asset:loan', array('list', 'records', 'detail'))
                ->allow('User', 'asset:message', array('list', 'records', 'add', 'delete', 'read'))
                ->allow('User', 'asset:request', array('list', 'records', 'add', 'detail'))
                ->allow('User', 'asset:upgrade', array('list', 'records', 'detail'));

        // Permission of Admin
        $this->allow('Admin', 'user:user', array('list', 'records', 'edit', 'detail'))
                ->allow('Admin', 'asset:history', array('list', 'records', 'detail'))
                ->allow('Admin', 'asset:item', array('list', 'records', 'detail', 'edit'))
                ->allow('Admin', 'asset:loan', array('list', 'records', 'add', 'delete', 'detail'))
                ->allow('Admin', 'asset:request', array('list', 'records', 'add', 'delete', 'accept', 'detail', 'disaccept'))
                ->allow('Admin', 'asset:upgrade', array('list', 'records', 'add', 'delete', 'detail'));

        // Permission of SuperAdmin
        $this->allow('SuperAdmin', 'user:user', array('list', 'records', 'edit', 'add', 'delete', 'detail'))
                ->allow('SuperAdmin', 'asset:item', array('list', 'records', 'detail', 'edit', 'add', 'delete'));
    }

}
