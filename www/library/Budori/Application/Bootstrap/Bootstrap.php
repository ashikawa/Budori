<?php
require_once 'Zend/Application/Bootstrap/Bootstrap.php';

/**
 * 一部のクラス(Budori_Db, Budori_Log...)がZend_Registryに依存しているため格上げ
 */
class Budori_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap 
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
			
			require_once 'Zend/Registry.php';
			$this->setContainer( Zend_Registry::getInstance() );
		}
		return $this->_container;
	}
}
