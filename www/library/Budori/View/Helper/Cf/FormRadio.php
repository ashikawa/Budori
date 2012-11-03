<?php
require_once 'Zend/View/Helper/FormElement.php';

class Budori_View_Helper_Cf_FormRadio extends Zend_View_Helper_FormElement
{
    protected $_inputType = 'radio';

    public function Cf_formRadio($name, $value = null, $attribs = null, $options = null)
    {

        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, value, attribs, options, listsep, disable

        if ($value === null) {
            $xhtml	= "&nbsp;";
        } else {
            $xhtml = $this->view->escape( $options[$value] );
        }

        return $xhtml;
    }
}
