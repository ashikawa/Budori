<?php

/**
 * Zend_Log ファクトリクラス
 * Zend_Registry に 'log' の名前で登録されている事。
 */
/**
 * 
 * @author shigeru.ashikawa
 */
class Budori_Log 
{
	/**
     * log factory
     * @return Zend_Log
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
