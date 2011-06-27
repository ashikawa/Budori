<?php
/**
 * 共通エスケープ関数
 * @param string $input
 * @return string
 */
function smarty_modifier_my_escape( $input )
{
	static $filter;
	
	if( !$filter ){
		$filter = new Budori_Filter_Escape();
	}
	
	return $filter->filter($input);
}
