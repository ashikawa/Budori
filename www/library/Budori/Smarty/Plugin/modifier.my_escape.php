<?php
/**
 * 共通エスケープ関数
 * @param string $input
 * @return string
 */
function smarty_modifier_my_escape( $input )
{	
	require_once 'Budori/Util/String.php';
	return Budori_Util_String::escape($input);
}
