<?php
/**
 * Smarty {zend_action} function plugin
 *
 * Smartyテンプレート内に、別のアクションコントローラの実行結果を埋め込む
 *
 * Input:
 *          - action		string　必須
 * 			- controlelr	string 必須
 * 			- module		string|null
 * 			- other			その他引数はパラメータとしてコントローラに渡される
 *
 * @param    array　$params
 * @param    Smarty $smarty
 * @return   string
 */
function smarty_function_zend_action( $params, &$smarty )
{
    require_once 'shared.helper_loader.php';
    $helper = smarty_helper_loader($params,$smarty);

    $action		= null;
    $controller	= null;
    $module		= null;
    $tmpParams	= array();

    foreach ($params as $_key => $_val) {
        switch ($_key) {
            case 'action':
            case 'controller':
            case 'module':
                $$_key = $_val;
                break;
            default:
                $tmpParams[$_key] = $_val;
                break;
        }
    }

    return $helper->action($action, $controller, $module, $tmpParams);
}
