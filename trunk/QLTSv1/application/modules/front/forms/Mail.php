<?php

class Front_Form_Mail extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init() {
        $name = new Zend_Form_Element_Text('Name');
        $name->setOptions(array(
                    'label' => 'Name',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $email = new Zend_Form_Element_Text('Email');
        $email->setOptions(array(
                    'label' => 'Email',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $subject = new Zend_Form_Element_Select('Subject');
        $subject->setOptions(array(
                    'label' => 'Subject',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $content = new Zend_Form_Element_Textarea('Content');
        $content->setOptions(array(
                    'label' => 'Content',
                    'style' => "width: 300px; height: 200px"
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formTextarea')),
                    array('Label', array('class' => 'label'))
                ));

        $this->setName('item-form')
                ->setMethod(Zend_Form::METHOD_POST)
                ->setEnctype(Zend_Form::ENCTYPE_URLENCODED)
                ->addElements(array($name, $email, $subject, $content))
                ->setDecorators(array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'fieldset', 'style' => "padding: 0; border: 0; margin-top: 25px;")),
                    'Form'
                ));
    }
}