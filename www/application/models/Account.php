<?php
/**
 * アカウントの制御
 */
class Account
{
	
	/**
	 * Enter description here...
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_table;
	
	
	/**
	 * Enter description here...
	 * @param Zend_Db_Adapter_Abstract $db
	 */
	public function __construct(Zend_Db_Adapter_Abstract $db )
	{
		$this->_table = new Neri_Db_Table_Member( $db );
	}
	
	
	/**
	 * テーブルオブジェクトの取得
	 * @return Neri_Db_Table_Member
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
	/**
	 * 登録処理
	 * @param array $params
	 * <pre>
	 * $params = array(
	 * 	'name'	=> ニックネーム
	 *  'id'	=> ID
	 *  'pass'	=> パスワード
	 * )
	 * </pre>
	 * @return mixed         The primary key of the row inserted
	 */
	public function create( $params )
	{
		$memberTable = $this->getTable();
		
//		$pass = new Zend_Db_Expr($memberTable->getAdapter()->quoteInto('MD5(?)',$params['pass']));
		
		$atters = array(
			'name'	=> $params['name'],
			'key'	=> $params['id'],
			'pass'	=> $params['pass'],
//			'pass'	=> $pass,
		);
		
		return $memberTable->insert( $atters );
	}
	
	public function read( $key )
	{
		$memberTable = $this->getTable();
		
		return $memberTable->find($key);
	}
	
	/**
	 * 登録処理
	 * @param array $params
	 * <pre>
	 * $params = array(
	 * 	'name'	=> ニックネーム
	 *  'mixi'	=> MIXI ID
	 *  'pass'	=> パスワード
	 * )
	 * </pre>
     * @return mixed The primary key value(s), as an associative array if the
     *     key is compound, or a scalar if the key is single-column.
	 */
	public function update( $memberId, $params )
	{
		$memberTable = $this->getTable();
		
		$row = $memberTable->find( $memberId )->current();
		
		return $row->setFromArray($params)->save();
	}
	
	/**
	 * 退会処理
	 * 
	 * @param string $memberId
     * @return mixed The primary key value(s), as an associative array if the
     *     key is compound, or a scalar if the key is single-column.
	 */
	public function delete( $memberId )
	{
		$memberTable = $this->getTable();
		
		$row = $memberTable->find( $memberId )->current();
		
		$data = array(
			'status'	=> false,
		);
		
		return $row->setFromArray($data)->save();
	}
}
