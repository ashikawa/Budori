<?php
/**
 * メッセージ出力　messages は一次配列のみ
 *
 * @param array $params
 * <pre>
 * Params:   script: string	使用するテンプレート
 *           other: その他のオプションは呼び出したテンプレート内部の変数としてアサインされる
 * </pre>
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_messages($params, &$smarty)
{
    require_once('shared.helper_loader.php');
	$helper = smarty_helper_loader($params,$smarty);
	
	$script	= 'helper/messages.phtml';
	$args = array();
	
	foreach ($params as $_key => $_value) {
		switch ($_key) {
			case 'script':
				$$_key = $_value;
				break;
			default:
				$args[$_key] = $_value;
				break;
		}
	}
	
	return $helper->partial($script,$args);
}
