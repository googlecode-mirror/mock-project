<?php

class ZendExt_Form_Decorator_Label extends Zend_Form_Decorator_Abstract {

    protected $_format = '<label for="%s">%s</label>';

    public function render($content) {
        $element = $this->getElement();
        $id = htmlentities($element->getId());
        $label = htmlentities($element->getLabel());
        $markup = sprint($this->_format, $id, $label);
        $placement = $this->getPlacement();
        $separator = $this->getSeparator();
        switch ($placement) {
            case self::APPEND:
                return $markup . $separator . $content;
            case self::PREPEND:
            default:
                return $content . $separator . $markup;
        }
    }

}

?>
