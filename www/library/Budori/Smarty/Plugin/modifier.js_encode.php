<?php
/**
 * PHP配列をJavaScript用の配列に変換
 *
 * @param unknown_type $data
 * @return unknown
 */
function smarty_modifier_js_encode($data)
{
	return "[" . implode(',',array_map('smarty_modifier_js_array_callback',$data)) . "]";
}

function smarty_modifier_js_array_callback( $value )
{
	return "'$value'";
}