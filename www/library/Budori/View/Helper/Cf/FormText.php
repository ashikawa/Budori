<?php
require_once 'Zend/View/Helper/FormElement.php';

class Budori_View_Helper_Cf_FormText extends Zend_View_Helper_FormElement
{
	public function Cf_formText($name, $value = null, $attribs = null)
	{
		$info = $this->_getInfo($name, $value, $attribs);
		extract($info); // name, value, attribs, options, listsep, disable
		
		if($value === ""){
			$xhtml	= "&nbsp;";
		}else {
			$xhtml	= $this->view->escape($value);
		}
		
        return $xhtml;
    }
}
