<?php

class Mail_AddressList_Dummy implements Mail_AddressList_Interface 
{
	
	/**
	 * @var unknown_type
	 */
	protected $_list = array();
	
	
	public function __construct()
	{
		$this->_initList();
	}
	
	protected function _initList()
	{
		$dummy = array(
			'dummy1@dummy.jp',
			'dummy2@dummy.jp',
		);
		
		
		$list = array();
		foreach ($dummy as $_value){
			$list[] = new Mail_Address($_value);
		}
		
		reset($list);
		$this->_list = $list;
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
