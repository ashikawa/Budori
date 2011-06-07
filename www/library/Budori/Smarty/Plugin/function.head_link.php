<?php
/**
 * Zend_View_Helper_HeadLink
 */
function smarty_function_head_link( $params, &$smarty )
{
    require_once('shared.helper_loader.php');
	$helper = smarty_helper_loader($params,$smarty);
	
	return $helper->HeadLink();
}
