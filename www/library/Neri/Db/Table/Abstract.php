<?php
require_once 'Zend/Db/Table/Abstract.php';

/**
 * Zend_Db_Table_Abstract　の若干の拡張
 * @todo Zend_Db_Table_Abstract::_getCols() はなんでプロテクトなんだろうか？
 * 		Budori_Db_Tableに移しても良いかも。
 */
abstract class Neri_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	public function getCols()
	{
		return $this->_getCols();
	}
}
