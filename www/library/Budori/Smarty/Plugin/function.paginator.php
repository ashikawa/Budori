<?php
/**
 * Zend_View_Helper_Paginator
 *
 * @param array $params
 * <pre>
 * Params:	class: Zend_Paginatorインスタンス
 * 			style: string (Sliding) Zend_Paginator の style 引数
 * 			script: string('helper/paginator.phtml')　使用するテンプレート
 * 			range: integer(10) Zend_Paginator->setPageRange()の値
 * </pre>
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_paginator( $params, &$smarty )
{
    require_once('shared.helper_loader.php');
	$helper = smarty_helper_loader($params,$smarty);
	
	$class	= null;
	$style	= null;
	$script	= null;
	$range	= 10;
	
	$args = array();
	
	foreach ($params as $_key => $_value) {
		switch ($_key) {
			case 'class':
			case 'style':
			case 'script':
				$$_key = $_value;
				break;
			case 'range':
				$$_key = intval($_value);
				break;
			default:
				$args[$_key] = $_value;
				break;
		}
	}
	
	if( !($class instanceof Zend_Paginator ) ){
		$smarty->trigger_error('$class is not instance of paginator');
		return ;
	}
	
	$class->setPageRange($range);
	return $helper->paginationControl( $class, $style, $script, $args );
}
