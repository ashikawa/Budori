<?php
require_once 'Smarty.class.php';

/**
 * Smarty　のラッパー
 * ※　これ以上の機能拡張はしない！！
 * 際限無いし、Smartyのバージョンアップに対応できなくなる可能性があるため。
 * 
 * Smarty3 から例外ちゃんと投げるようになったので、その役目を終えた。
 */
class Budori_Smarty extends Smarty 
{
//	
//	/**
//	 * エラー時に例外を投げるか
//	 * @var boolean
//	 */
//	public $throw_exception = true;
//	
//	/**
//	 * over ride
//	 * Smartyの汎用エラー処理を例外に変換
//	 * 
//	 * @param string $error_msg
//	 * @param integer $error_type
//	 */
//	public function trigger_error( $error_msg, $error_type = E_USER_WARNING )
//	{
//		if( $this->throw_exception ){
//			require_once 'Budori/Smarty/Exception.php';
//			throw new Budori_Smarty_Exception( $error_msg );
//		}else{
//			parent::trigger_error( $error_msg, $error_type );
//		}
//	}
}
