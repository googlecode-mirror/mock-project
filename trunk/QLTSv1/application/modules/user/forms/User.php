<?php

class User_Form_User extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init() {
//        if (!$this->hasTranslator()) {
//            $this->setTranslator(Zend_Registry::get('Zend_Translate'));
//        }

        $userid = new Zend_Form_Element_Hidden('UserID');
        $userid->setDecorators(array(
            array('ViewHelper',
                array('helper' => 'formHidden'))
        ));

        $username = new Zend_Form_Element_Text('Username');
        $username->setOptions(array(
                    'label' => 'Username',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper',
                        array('helper' => 'formText')),
                    array('Label',
                        array('class' => 'label'))
                ));

//        $password = new Zend_Form_Element_Password('Password');
//        $password->setOptions(array(
//                    'label' => 'Password',
//                    'required' => TRUE
//                ))
//                ->setDecorators(array(
//                    array('ViewHelper',
//                        array('helper' => 'formPassword')),
//                    array('Label',
//                        array('class' => 'label'))
//                ));

        $role = new Zend_Form_Element_Select('Role');
        $role->setOptions(array(
                    'label' => 'Role',
                    'MultiOptions' => array(3 => 'User', 2 => 'IT', 1 => 'Admin', 0 => 'SuperAdmin')
                ))
                ->setDecorators(array(
                    array('ViewHelper',
                        array('helper' => 'formSelect')),
                    array('Label',
                        array('class' => 'label'))
                ));

        $fullname = new Zend_Form_Element_Text('FullName');
        $fullname->setOptions(array(
                    'label' => 'FullName'
                ))
                ->setDecorators(array(
                    array('ViewHelper',
                        array('helper' => 'formText')),
                    array('Label',
                        array('class' => 'label'))
                ));

        $email = new Zend_Form_Element_Text('Email');
        $email->setOptions(array(
                    'label' => 'Email',
                    'required' => TRUE
                ))
                ->setDecorators(array(
                    array('ViewHelper',
                        array('helper' => 'formText')),
                    array('Label',
                        array('class' => 'label'))
                ));

        $group = new Zend_Form_Element_Text('Group');
        $group->setOptions(array(
                    'label' => 'Group'
                ))
                ->setDecorators(array(
                    array('ViewHelper',
                        array('helper' => 'formText')),
                    array('Label',
                        array('class' => 'label'))
                ));

        $phone = new Zend_Form_Element_Text('Phone');
        $phone->setOptions(array(
                    'label' => 'Phone'
                ))
                ->setDecorators(array(
                    array('ViewHelper',
                        array('helper' => 'formText')),
                    array('Label',
                        array('class' => 'label'))
                ));

        $address = new Zend_Form_Element_Textarea('Address');
        $address->setOptions(array(
                    'label' => 'Address',
                    'style' => "width: 200px; height: 150px"
                ))
                ->setDecorators(array(
                    array('ViewHelper',
                        array('helper' => 'formTextarea')),
                    array('Label',
                        array('class' => 'label'))
                ));

        $this->setName('user-form')
                ->setMethod(Zend_Form::METHOD_POST)
                ->setEnctype(Zend_Form::ENCTYPE_URLENCODED)
                ->addElements(array($userid, $username, $role, $fullname, $email, $group, $phone, $address))
                ->setDecorators(array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'fieldset', 'style' => "padding: 0; border: 0; margin-top: 25px;")),
                    'Form'
                ));
    }

}
