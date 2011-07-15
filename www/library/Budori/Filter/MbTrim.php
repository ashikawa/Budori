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
	public function filter( $value )
	{
		return self::mbTrim($value);
    }
    
	/**
	 * マルチバイトのTrim
	 *　
	 * @param string $str
	 * @param string $chars
	 * @return Budori_String
	 */
	public static function mbTrim( $string )
	{
		return trim( $string, " 　\t\n\rv");
	}
}