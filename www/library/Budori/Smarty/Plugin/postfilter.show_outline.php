<?php
/**
 * 開発用ツール
 * includeされたSmartyのテンプレートにグリッドを表示する。
 * ※タグの構造が壊れる事もある。
 * Smarty->force_compile	= true	を推奨
 * 
 * @param string $output
 * @param Smarty $smarty
 * @return string
 */
function smarty_postfilter_show_outline($output, &$smarty)
{
	return	'<fieldset style="border:1px solid #ff9966;padding:3px;margin:3px;">'	.	PHP_EOL
		.	'<legend style="font-size:12px;">' . $smarty->_current_file . '</legend>'	.	PHP_EOL
		.	$output	.	PHP_EOL
		.	'</fieldset>'	.	PHP_EOL;
	
}
