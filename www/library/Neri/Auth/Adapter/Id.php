<?php
/**
 * IDのみ、認証なしログイン
 */
class Neri_Auth_Adapter_Id extends Neri_Auth_Adapter_DbAbstract 
{
	protected function _authenticateCreateSelect()
	{
		require_once 'Neri/Db/Select/Member.php';
		$query = new Neri_Db_Select_Member($this->_db);
		$query->setKey($this->_identity)
				->setDefault();
		
		return $query;
	}
	
	protected function _authenticateValidateResultSet( $result )
	{
		require_once 'Zend/Auth/Result.php';
		
		if( count($result) == 1 ){
			
			$this->_resultRow = $result[0];
			
			$this->_code = Zend_Auth_Result::SUCCESS;
			
			$this->setIdentity( $result[0]->key );
			
		}else if(count($result) == 0){
			
			$this->_code = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
			
		}else if (count($result) > 0){
			
			$this->_code = Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
		}
	}
}
