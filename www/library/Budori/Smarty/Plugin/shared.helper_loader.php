<?php

/**
 * Function: smarty_helper_loader<br>
 * Purpose:  Zend_View_Heplerの呼び出し
 * 			　詳細は Budori_View_Smarty と Budori_View_Plugin に依存
 *　			　Helperを使用するプラグインは、この関数経由で呼び出す事。
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return Budori_View_Plugin
 */
function smarty_helper_loader( $params, &$smarty )
{
	if( !isset($smarty->_plugins['helper']) ){
		throw new SmartyException('cannot read helper');
//		$smarty->trigger_error('cannot read helper');
	}
	
	return $smarty->_plugins['helper'];
}
