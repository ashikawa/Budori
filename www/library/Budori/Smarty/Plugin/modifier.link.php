<?php
/**
 * Smarty link modifer plugin
 * 
 * 第一パラメータ中からダブルクオート、シングルクオートで始まっていないURLとメールアドレスを探し、リンクに直す。
 * 
 * @param string $string
 * @return string
 * 
 * @uses smarty_modifier_link_callback()
 */
function smarty_modifier_link( $string )
{
	
	$pattern_url = 	"/" . Budori_Const::PATTERN_URL . "/i";
	$string = preg_replace_callback($pattern_url, 'smarty_modifier_link_callback_url', $string );
	
	$pattern_mail = "/".Budori_Const::PATTERN_MAILADDR."/";
	$string = preg_replace_callback( $pattern_mail, 'smarty_modifier_link_callback_mail', $string );
	
	return $string;
}


	
/**
 * URLのリンク変更用コールバック関数
 * 同じドメインの場合は同じウインドウ
 * 
 * @param string $string
 * @return string
 */
function smarty_modifier_link_callback_url( $string )
{
	
	$value = $string[0];
	
	if( isset($_SERVER['SERVER_NAME']) && preg_match( "/".$_SERVER['SERVER_NAME']."/i", $value ) ){
		
		return "<a href=\"$value\">$value</a>";
	}else {
		
		return "<a href=\"$value\" target=\"_blank\">$value</a>";
	}
}

/**
 * メールの暗号化用コールバック関数
 *
 * @param string $string
 * @return string
 */
function smarty_modifier_link_callback_mail( $string )
{
	
	$value = $string[0];
	$length = strlen($value);
	
	$return = '';
	
	for ( $x=0; $x < $length; $x++ ) {
		$return .= '&#x' . bin2hex($value[$x]) . ";";
	}
	
	return "<a href=\"mailto:$return\">$return</a>";
}
