<?php

class ZendExt_Acl extends Zend_Acl {

    public function __construct() {
        // User type
        $this->addRole(new Zend_Acl_Role(NULL));
        $this->addRole(new Zend_Acl_Role('users'), NULL);
        $this->addRole(new Zend_Acl_Role('admins'), 'users');
        // module default
        $this->add(new Zend_Acl_Resource('default'))
                ->add(new Zend_Acl_Resource('default:comment'), 'default')
                ->add(new Zend_Acl_Resource('default:contact'), 'default')
                ->add(new Zend_Acl_Resource('default:error'), 'default')
                ->add(new Zend_Acl_Resource('default:file'), 'default')
                ->add(new Zend_Acl_Resource('default:index'), 'default')
                ->add(new Zend_Acl_Resource('default:notice'), 'default')
                ->add(new Zend_Acl_Resource('default:test'), 'default')
                ->add(new Zend_Acl_Resource('default:time'), 'default')
                ->add(new Zend_Acl_Resource('default:translate'), 'default');
        // module user
        $this->add(new Zend_Acl_Resource('user'))
                ->add(new Zend_Acl_Resource('user:login'), 'user')
                ->add(new Zend_Acl_Resource('user:manage'), 'user')
                ->add(new Zend_Acl_Resource('user:profile'), 'user')
                ->add(new Zend_Acl_Resource('user:register'), 'user');
        // module admin
        $this->add(new Zend_Acl_Resource('admin'))
                ->add(new Zend_Acl_Resource('admin:file'), 'admin')
                ->add(new Zend_Acl_Resource('admin:index'), 'admin')
                ->add(new Zend_Acl_Resource('admin:notice'), 'admin')
                ->add(new Zend_Acl_Resource('admin:subject'), 'admin')
                ->add(new Zend_Acl_Resource('admin:user'), 'admin');
        //
        $this->allow(NULL, 'default:contact', array('index', 'success'))
                ->allow(NULL, 'default:error', 'error')
                ->allow(NULL, 'default:file', array('index'))
                ->allow(NULL, 'default:index', array('index', 'about', 'link'))
                ->allow(NULL, 'default:notice', array('index', 'show'))
                ->allow(NULL, 'default:test', array('index'))
                ->allow(NULL, 'default:time', 'index')
                ->allow(NULL, 'default:translate', 'index')
                ->deny(NULL, 'user')
                ->allow(NULL, 'user:login', 'login')
                ->allow(NULL, 'user:register', array('index', 'activate', 'ajax', 'success'))
                ->deny(NULL, 'admin')
                ->allow(NULL, 'admin:file', array('index'));
        //
        $this->allow('users', 'user:login', array('success', 'logout'))
                ->allow('users', 'default:comment', array('add', 'delete'))
                ->allow('users', 'user:profile', array('index', 'edit', 'repassword', 'chpassword'))
                ->deny('users', 'user:login', 'login')
                ->deny('users', 'admin:index', 'index')
                ->deny('users', 'admin');
        //
        $this->allow('admins', 'admin:file', array('index', 'upload', 'download', 'delete'))
                ->allow('admins', 'admin:index', 'index')
                ->allow('admins', 'admin:notice', array('index', 'add', 'edit', 'delete'))
                ->allow('admins', 'admin:subject', array('index', 'add', 'edit', 'delete'))
                ->allow('admins', 'user:manage', array('index', 'add', 'edit', 'ban', 'delete'));
    }

}
