<?php

/**
 * Zend_Db_Adapter ファクトリクラス
 * Zend_Registry に 'db' の名前で登録されている事。
 */
class Budori_Db 
{
	/**
	 * @return Zend_Db_Adapter_Abstract
	 */
	public static function factory()
	{
		require_once 'Zend/Registry.php';
    	$db = Zend_Registry::get('db');
    	
    	if( !( $db instanceof Zend_Db_Adapter_Abstract ) ){
    		throw new Budori_Exception('cannot find db object in register');
    	}
    	
    	return $db;
	}
}
