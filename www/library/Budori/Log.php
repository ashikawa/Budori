<?php

/**
 * Zend_Log ファクトリクラス
 * Zend_Registry に 'log' の名前で登録されている事。
 */
class Budori_Log 
{
	/**
     * Enter description here...
     * @return Budori_Log_Interface
     */
    public static function factory()
    {
    	$log = Zend_Registry::get('log');
    	
    	if( !($log instanceof Zend_Log) ){
    		
    		require_once 'Budori/Exception.php';
    		throw new Budori_Exception('cannot find log object in register');
    	}
    	
    	return $log;
    }
    
}
