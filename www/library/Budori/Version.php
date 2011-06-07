<?php
/**
 * ライブラリのバージョン
 */
class Budori_Version
{
	const VERSION		= '1.0.0';
	
	const ORDER_ZEND	= '1.10.0';
	
	/**
	 * Zendのライブラリが対応しているか調べる
	 * @return boolean
	 */
	public static function checkZendVersion()
	{
		require_once 'Zend/Version.php';
		return Zend_Version::compareVersion( self::ORDER_ZEND );
	}
}
