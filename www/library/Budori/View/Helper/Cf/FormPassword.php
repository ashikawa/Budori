<?php
require_once 'Zend/View/Helper/FormElement.php';

class Budori_View_Helper_Cf_FormPassword extends Zend_View_Helper_FormElement
{
    public function Cf_formPassword($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info);

        if ($value === "") {
            $xhtml	= "&nbsp;";
        } else {
            $xhtml	= str_pad("",mb_strlen($value),'*');
        }

        return $xhtml;
    }
}
