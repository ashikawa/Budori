<?php
/**
 * Zend_View_Helper_HeadMeta
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string | null
 */
function smarty_function_head_meta( $params, &$smarty )
{
    require_once('shared.helper_loader.php');
	$helper = smarty_helper_loader($params,$smarty);
	
	if(!empty($params)){
		foreach ( $params as $key => $value ){
			$helper->HeadMeta()->appendName($key, $value);
		}
		return null;
	}else{
		return $helper->HeadMeta();
	}
}
