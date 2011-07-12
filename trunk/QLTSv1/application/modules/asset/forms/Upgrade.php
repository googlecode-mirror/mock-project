<?php

class Asset_Form_Upgrade extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init() {
//        if (!$this->hasTranslator()) {
//            $this->setTranslator(Zend_Registry::get('Zend_Translate'));
//        }
        $upgradeid = new Zend_Form_Element_Hidden('UpgradeID');
        $upgradeid->setDecorators(array(
            array('ViewHelper',
                array('helper' => 'formHidden'))
        ));
        
        $maTS = new Zend_Form_Element_Text('MaTS');
        $maTS->setOptions(array(
                    'label' => 'Ma Tai San',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $Username = new Zend_Form_Element_Text('Username');
        $Username->setOptions(array(
                    'label' => 'Username',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $Manager = new Zend_Form_Element_Text('Manager');
        $Manager->setOptions(array(
                    'label' => 'Manager',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $date = new Zend_Form_Element_Text('Date');
        $date->setOptions(array(
                    'label' => 'Date',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $detail = new Zend_Form_Element_Textarea('Detail');
        $detail->setOptions(array(
                    'label' => 'Detail',
                    'style' => "width: 200px; height: 100px"
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formTextarea')),
                    array('Label', array('class' => 'label'))
                ));

        $this->setName('item-form')
                ->setMethod(Zend_Form::METHOD_POST)
                ->setEnctype(Zend_Form::ENCTYPE_URLENCODED)
                ->addElements(array($upgradeid, $maTS, $Username, $Manager, $date, $detail))
                ->setDecorators(array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'fieldset', 'style' => "padding: 0; border: 0; margin-top: 25px;")),
                    'Form'
                ));
    }

}
