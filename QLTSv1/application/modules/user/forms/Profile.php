<?php

class User_Form_Profile extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init() {
//        if (!$this->hasTranslator()) {
//            $this->setTranslator(Zend_Registry::get('Zend_Translate'));
//        }

        $userid = new Zend_Form_Element_Hidden('UserID');

        $username = new Zend_Form_Element_Text('Username');
        $username->setOptions(array(
            'label' => 'Username',
            'required' => TRUE,
            'filters' => array('StringTrim')
        ));

        $password = new Zend_Form_Element_Password('Password');
        $password->setOptions(array(
            'label' => 'Password',
            'required' => TRUE
        ));
        $repassword = new Zend_Form_Element_Password('RePassword');
        $repassword->setOptions(array(
            'label' => 'Retype Password',
            'required' => TRUE
        ));

        $role = new Zend_Form_Element_Hidden('Role');

        $fullname = new Zend_Form_Element_Text('FullName');
        $fullname->setOptions(array(
            'label' => 'FullName'
        ));

        $email = new Zend_Form_Element_Text('Email');
        $email->setOptions(array(
            'label' => 'Email',
            'required' => TRUE
        ));

        $birthday = new Zend_Form_Element_Text("Birthday");
        $birthday->setOptions(array(
            'label' => 'Birthday'
        ));

        $group = new Zend_Form_Element_Text('Group');
        $group->setOptions(array(
            'label' => 'Group'
        ));

        $phone = new Zend_Form_Element_Text('Phone');
        $phone->setOptions(array(
            'label' => 'Phone'
        ));

        $address = new Zend_Form_Element_Text('Address');
        $address->setOptions(array(
            'label' => 'Address'
        ));

        $submit = new Zend_Form_Element_Submit('Submit');

        $this->setName('profile-form')
                ->setMethod(Zend_Form::METHOD_POST)
                ->setEnctype(Zend_Form::ENCTYPE_URLENCODED)
                ->addElements(array($userid, $username, $password, $repassword, $role, $fullname, $email, $birthday, $group, $phone, $address, $submit))
                ->setDecorators(array(
                    'FormElements',
                    'Errors',
                    array('HtmlTag', array('tag' => 'table', 'cellpadding' => '3')),
                    'Form'
                ));
    }

}