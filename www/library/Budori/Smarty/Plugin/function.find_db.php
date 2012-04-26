<?php
/**
 * Smarty {find_db} function plugin
 * 
 * Input:
 * 		- table
 * 		- id
 * 		- column
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string
 * 
 * @todo register cache
 */
function smarty_function_find_db( $params, &$smarty )
{	
	$table	= "";
	$id		= "";
	$column	= "";
	
	foreach ($params as $_key => $_value) {
		switch ($_key) {
			case 'table':
			case 'id':
			case 'column':
				$$_key = $_value;
				break;
		}
	}
	
	$tableClass = new Zend_Db_Table($table);
	
	return $tableClass->find($id)->current()->{$column};
}