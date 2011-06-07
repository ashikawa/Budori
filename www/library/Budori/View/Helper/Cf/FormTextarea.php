<?php
require_once 'Zend/View/Helper/FormElement.php';

class Budori_View_Helper_Cf_FormTextarea extends Zend_View_Helper_FormElement 
{
	public function Cf_formTextarea($name, $value = null, $attribs = null)
	{
		$info = $this->_getInfo($name, $value, $attribs);
		extract($info);
		
		if($value === ""){
			$xhtml	= "&nbsp;";
		}else {
			$xhtml	= $this->view->escape($value);
		}
		
		return nl2br($xhtml);
    }
}
