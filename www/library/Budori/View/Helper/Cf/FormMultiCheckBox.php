<?php
require_once 'Zend/View/Helper/FormElement.php';

class Budori_View_Helper_Cf_FormMultiCheckbox extends Zend_View_Helper_FormElement  
{
	/**
	 * Input type to use
	 * @var string
	 */
	protected $_inputType = 'checkbox';
	
	/**
	 * Whether or not this element represents an array collection by default
	 * @var bool
	 */
	protected $_isArray = true;
	
	public function Cf_formMultiCheckbox($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n")
	{
		
		if(is_null($value)){
			return "&nbsp;";
		}
		
		$value = (array) $value;
		
		$xhtml = "";
		
		foreach ($value as $_v){
			if(array_key_exists($_v, $options)){
				
				$xhtml .= $options[$_v] . $listsep;
				
			}
		}
		
		return $xhtml;
	}
}
