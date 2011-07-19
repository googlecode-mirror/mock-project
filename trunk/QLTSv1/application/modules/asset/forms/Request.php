<?php

class Asset_Form_Request extends Zend_Form {
    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init() {
//        if (!$this->hasTranslator()) {
//            $this->setTranslator(Zend_Registry::get('Zend_Translate'));
//        }
        $requestid = new Zend_Form_Element_Hidden('RequestID');
        $requestid->setDecorators(array(
            array('ViewHelper',
                array('helper' => 'formHidden'))
        ));
        
        $maTS = new Zend_Form_Element_Text('MaTS');
        $maTS->setOptions(array(
                    'label' => 'Mã TS',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));
//
//        $UserID = new Zend_Form_Element_Text('UserID');
//        $UserID->setOptions(array(
//                    'label' => 'UserID',
//                    'required' => TRUE,
//                    'filters' => array('StringTrim')
//                ))
//                ->setDecorators(array(
//                    array('ViewHelper', array('helper' => 'formText')),
//                    array('Label', array('class' => 'label'))
//                ));

        $utype = new Zend_Form_Element_Select('Type');
        $utype->setOptions(array(
                    'label' => 'Loại yêu cầu',
                    'required' => TRUE,
                    'MultiOptions' => array(0 => 'Yêu cầu mượn TS', 1 => 'Yêu cầu nâng cấp')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formSelect')),
                    array('Label', array('class' => 'label'))
                ));

        $detail = new Zend_Form_Element_Textarea('Detail');
        $detail->setOptions(array(
                    'label' => 'Chi tiết yêu cầu',
                    'style' => "width: 200px; height: 100px"
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formTextarea')),
                    array('Label', array('class' => 'label'))
                ));

        $this->setName('item-form')
                ->setMethod(Zend_Form::METHOD_POST)
                ->setEnctype(Zend_Form::ENCTYPE_URLENCODED)
                ->addElements(array($requestid, $maTS, $utype, $detail))
                ->setDecorators(array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'fieldset', 'style' => "padding: 0; border: 0; margin-top: 25px;")),
                    'Form'
                ));
    }
}

