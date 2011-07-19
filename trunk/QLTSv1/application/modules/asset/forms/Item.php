<?php

class Asset_Form_Item extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init() {
//        if (!$this->hasTranslator()) {
//            $this->setTranslator(Zend_Registry::get('Zend_Translate'));
//        }

        $itemid = new Zend_Form_Element_Hidden('ItemID');
        $itemid->setDecorators(array(
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

        $tenTS = new Zend_Form_Element_Text('TenTS');
        $tenTS->setOptions(array(
                    'label' => 'Tên tài sản',
                    'required' => TRUE,
                    'filters' => array('StringTrim')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $descr = new Zend_Form_Element_Textarea('Description');
        $descr->setOptions(array(
                    'label' => 'Mô tả',
                    'style' => "width: 200px; height: 50px"
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formTextarea')),
                    array('Label', array('class' => 'label'))
                ));

        $type = new Zend_Form_Element_Select('Type');
        $type->setOptions(array(
                    'label' => 'Loại bảo mật',
                    'required' => TRUE,
                    'MultiOptions' => array(1 => 'Bảo mật thấp', 0 => 'Bảo mật cao')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formSelect')),
                    array('Label', array('class' => 'label'))
                ));

        $startDate = new Zend_Form_Element_Text('StartDate');
        $startDate->setOptions(array(
                    'label' => 'Bắt đầu SD',
                    'required' => TRUE
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $price = new Zend_Form_Element_Text('Price');
        $price->setOptions(array(
                    'label' => 'Giá',
                    'required' => TRUE,
                    'filters' => array('Int')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $warrantyTime = new Zend_Form_Element_Text('WarrantyTime');
        $warrantyTime->setOptions(array(
                    'label' => 'Bảo hành',
                    'required' => TRUE,
                    'filters' => array('Int')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formText')),
                    array('Label', array('class' => 'label'))
                ));

        $status = new Zend_Form_Element_Select('Status');
        $status->setOptions(array(
                    'label' => 'Tình trạng',
                    'required' => TRUE,
                    'MultiOptions' => array(0 => 'Có thể mượn', 1 => 'Đang cho mượn', 2 => 'Hỏng')
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formSelect')),
                    array('Label', array('class' => 'label'))
                ));

        $place = new Zend_Form_Element_Textarea('Place');
        $place->setOptions(array(
                    'label' => 'Địa điểm hiện tại',
                    'style' => "width: 200px; height: 50px"
                ))
                ->setDecorators(array(
                    array('ViewHelper', array('helper' => 'formTextarea')),
                    array('Label', array('class' => 'label'))
                ));

        $this->setName('item-form')
                ->setMethod(Zend_Form::METHOD_POST)
                ->setEnctype(Zend_Form::ENCTYPE_URLENCODED)
                ->addElements(array($itemid, $maTS, $tenTS, $descr, $type, $startDate, $price, $warrantyTime, $status, $place))
                ->setDecorators(array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'fieldset', 'style' => "padding: 0; border: 0; margin-top: 25px;")),
                    'Form'
                ));
    }

}
