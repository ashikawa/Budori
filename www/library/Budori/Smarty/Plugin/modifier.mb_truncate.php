<?php
/**
 * Smarty text_thunbnail modifer plugin
 * 
 * <pre>
 * テキストをサムネイル用に編集する。
 * 改行タグを削除、第二パラメータで文字数指定。
 * </pre>
 * 
 * @param string $string
 * @param unknown_type $length
 * @return string
 */
function smarty_modifier_mb_truncate( $string, $length=null, $mark="..." )
{
	$result = "";
	
	if( $length != null ){
		$result = mb_substr( $string, 0, $length );
	}
	
	if( $length != null && mb_strlen($string) > $length ){
		$result .= $mark;
	}
	
	return $result;
}
