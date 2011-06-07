<?php
require_once 'Zend/Filter/StringTrim.php';

/**
 * Zend_Filter_StringTrimの全角対応
 */
class Budori_Filter_MbTrim extends Zend_Filter_StringTrim 
{
	/**
     * Defined by Zend_Filter_Interface
     * 
     * Returns the string $value with characters stripped from the beginning and end
	 *　
	 * @param string $value
	 * @return string
	 */
    public function filter($value)
    {
    	require_once 'Budori/Util/String.php';
    	
        if (null === $this->_charList) {
            return Budori_Util_String::mbTrim((string) $value);
        } else {
            return Budori_Util_String::mbTrim((string) $value, $this->_charList);
        }
    }
}
