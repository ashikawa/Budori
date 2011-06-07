<?php
require_once 'Zend/Validate/Abstract.php';

/**
 * 禁止ワードリストに登録されているかどうか
 */
class Budori_Validate_DenyWord extends Zend_Validate_Abstract
{
	
	/**
	 * 禁止ワードリスト
	 * @var array
	 */
	protected $_denylist;
	
	
	const DENY_EXISTS = 'denyExists';
    
	
    protected $_messageTemplates = array(
        self::DENY_EXISTS   => "deny word exists",
    );
    
    
    /**
     * 禁止ワードリストの初期化
     */
    public function __construct($list)
    {
		if ($list instanceof Zend_Config) {
			$list = $list->toArray();
		}
		
		if (is_set($list['list'])) {
			$list = $list['list'];
		}
		
		$this->setPattern($list);
	}
	
   	/**
   	 * 禁止ワードリストのセット
   	 */
	public function setDenyList($list)
   	{
   		$this->_denylist = $list;
   	}
   	
    /**
     * バリデート実行
     *
     * @param stging $value
     * @return boolean
     * 
     * @uses DenywordDataAccess::select()
     */
    public function isValid($value)
    {
    	
    	if( empty( self::$_denylist ) ){
    		require_once 'Zend/Validate/Exception.php';
    		throw new Zend_Validate_Exception('not found denyWordList');
		}
		
		$valueString = (string) $value;
		
		$this->_setValue($valueString);
		
		if ('' === $valueString) {
            return true;
        }
        
        $record = self::$_denylist;
        
		foreach ( $record as $var ){
			if( false !== stristr( $value,$var ) ){
	            $this->_error(self::DENY_EXISTS);
				return false;
			}
		}
		
        return true;
    }

}
