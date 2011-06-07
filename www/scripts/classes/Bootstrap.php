<?php

/**
 * バッチ処理サンプル
 * とりあえず、必要な処理を書き出し。
 * 
 */
class Bootstrap extends Zend_Application_Bootstrap_BootstrapAbstract 
{
	/**
	 * Retrieve resource container
	 * コンテナを Zend_Registry(static ver) に変更。
	 * これは、各リソースにフロントコントローラ経由無しにアクセスするためのもの。
	 * ex) Zend_Registry::get(....)
	 * 
	 * @return object
	 */
	public function getContainer()
	{
		if (null === $this->_container) {
			$this->setContainer( Zend_Registry::getInstance() );
		}
		return $this->_container;
	}
	
	/**
	 * Zend_Db_AdapterのFetchModel設定。
	 * @return null
	 */
	protected function _initFetch()
	{
		$db = $this->bootstrap('db')->getResource('db');
		
		if( !is_null($db) ){
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
		}
		
		return null;
	}
	
	/**
	 * データベースの設定
	 * @return null
	 */
	protected function _initDbTable()
	{
		$cache = $this->bootstrap('cachemanager')
						->getResource('cachemanager');
		
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache->getCache('file'));
		
		return null;
	}
}
