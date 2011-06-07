<?php
/**
 * 配列アサイン
 * 
 * @param array $params
 * @param Smarty $smarty
 */
function smarty_function_assigns($params, &$smarty)
{
	$smarty->assign($params);
}
