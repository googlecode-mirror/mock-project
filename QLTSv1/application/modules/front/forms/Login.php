<?php

class front_Form_Login extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init() {
//        if (!$this->hasTranslator()) {
//            $this->setTranslator(Zend_Registry::get('Zend_Translate'));
//        }

        $username = new Zend_Form_Element_Text('username');
        $username->setOptions(array(
            'label' => 'Username',
            'required' => TRUE,
            'filters' => array('StringTrim')
        ));

        $password = new Zend_Form_Element_Password('password');
        $password->setOptions(array(
            'label' => 'Password',
            'required' => TRUE
        ));

        $login = new Zend_Form_Element_Submit('login');
        $login->setOptions(array(
            'label' => 'Login'
        ));

        $this->setName('login-form')
                ->setMethod(Zend_Form::METHOD_POST)
                ->setEnctype(Zend_Form::ENCTYPE_URLENCODED)
                ->addElements(array($username, $password, $login))
                ->setDecorators(array(
                    'FormElements',
                    'Errors',
                    array('HtmlTag', array('tag' => 'table', 'cellpadding' => '3')),
                    'Form'
                ));
    }
}
