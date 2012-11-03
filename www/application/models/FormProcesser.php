<?php

/**
 * Zend_Form 加工用クラス
 */
class FormProcesser
{
    /**
     * Enter description here...
     * @var Zend_Form
     */
    protected $_form;

    /**
     * Enter description here...
     * @param Zend_Form $form
     */
    public function __construct(Zend_Form $form)
    {

//		副作用ありそうな場合はオブジェクトのコピー。
//		$this->_form = clone $form;

        $this->_form = $form;
    }

    /**
     * Enter description here...
     * @return Zend_Form
     */
    public function getForm()
    {
        return $this->_form;
    }

    public function buildConfigPage()
    {
        $form = $this->getForm();

        foreach ($form->getElements() as $_name => $_element) {
            if ($_element instanceof Zend_Form_Element) {

                if( ($_element instanceof Zend_Form_Element_Submit)
                        || $_element instanceof Zend_Form_Element_Image
                        || $_element instanceof Zend_Form_Element_Reset
                        || $_element instanceof Zend_Form_Element_Hidden ){

                    $form->removeElement($_name);

                    continue;
                }

                $_element->removeDecorator('description');

                $_element->helper = 'Cf_' .  $_element->helper;
            }
        }

        return $form;
    }

    public function buildMessageBody()
    {
        $form = $this->getForm();

        $str = "";

        foreach ($form->getElements() as $_name => $_element) {

            if ($_element instanceof Zend_Form_Element) {

                $output = "";

                switch (true) {
                    case ( $_element instanceof Zend_Form_Element_Submit ):
                    case ( $_element instanceof Zend_Form_Element_Image  ):
                    case ( $_element instanceof Zend_Form_Element_Reset  ):
                        break;

                    case ( $_element instanceof Zend_Form_Element_Multi ):

                        $options = $_element->getMultiOptions();
                        $val = $_element->getValue();

                        if (is_array($val)) {
                            foreach ($val as $_k => $_v) {
                                $output .= $_v . ":" . $options[$_v] . PHP_EOL;
                            }

                        } elseif (array_key_exists($val, $options)) {
                            $output = $val . ":" . $options[$val];
                        } else {
                            $output = "";
                        }

                        break;

                    default:
                        $output = $_element->getValue();
                        break;
                }

                $label = $_element->getLabel();

                if (!is_null($label)) {
                    $str .=  $label . PHP_EOL;
                }

                $str .=  $output . PHP_EOL . PHP_EOL;
            }
        }

        return $str;
    }

    //public function sendMail();
}
