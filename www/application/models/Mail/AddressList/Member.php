<?php
/**
 * @todo データを保管する役割と、初期化する役割でクラスを分割した方が良い気がする。
 * @todo Mail_AddressList_DbSelect とかにして、Select　は外から投げ込む?
 */
class Mail_AddressList_Member implements Mail_AddressList_Interface 
{
	/**
	 * Enter description here...
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_dbAdapter;
	
	/**
	 * @var unknown_type
	 */
	protected $_list = array();
	
	
	public function __construct(Zend_Db_Adapter_Abstract $dbAdapter)
	{
		$this->_dbAdapter = $dbAdapter;
		$this->_initList();
	}
	
	protected function _initList()
	{
		$select = new Neri_Db_Select_Address($this->_dbAdapter);
		$select->setDefault();
		
		$rows = $this->_dbAdapter->fetchAll($select);
		
		$_list = array();
		foreach ( $rows as $_k => $_v ){
			
			$_list[] = new Mail_Address($_v->value, null, (array)$_v);
		}
		
		reset($_list);
		$this->_list = $_list;
	}
	
	public function current()
	{
		return current($this->_list);
	}
	
	public function key()
	{
		return key($this->_list);
	}
	
	public function next()
	{
		next($this->_list);
	}
	
	public function rewind()
	{
		reset($this->_list);
	}
	
	public function valid()
	{
		return ($this->current() !== false);
	}
}
