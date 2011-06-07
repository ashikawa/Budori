<?php

/**
 * 会員の権限、プロパティ etc...
 * Zend_Db_Table の DefaultAdapter が必須
 * @todo 抽象化、汎用化するか悩み中。　
 */
class Neri_Member_Profile 
{
	/**
	 * Enter description here...
	 * @return Zend_Db_Table_Row_Abstract
	 */
	protected $_propaty;
	
	/**
	 * Enter description here...
	 * 
	 * @param Zend_Db_Adapter_Abstract $db
	 * @param string $key
	 */
	public function __construct( $key )
	{
		require_once 'Neri/Db/Table/Member.php';
		
		$table = new Neri_Db_Table_Member();
		$rows = $table->find($key);
		
		if( $rows->count() != 1){
			require_once 'Budori/Member/Exception.php';
			throw new Budori_Member_Exception('member error　not found record');
		}
		
		$this->_propaty = $rows->current();
	}
	
	/**
	 * Enter description here...
	 */
	public function save()
	{
		$this->_propaty->save();
	}
	
	/**
	 * Enter description here...
	 */
	public function toArray()
	{
		return $this->_propaty->toArray();
	}
	
	/**
	 * メンバープロパティの取得
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->_propaty->$name;
	}
	
	/**
	 * メンバープロパティの設定
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name,$value)
	{
		$this->_propaty->$name = $value;
	}
	
}
