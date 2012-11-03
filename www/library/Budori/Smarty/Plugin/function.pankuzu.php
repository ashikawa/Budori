<?php
/**
 * Budori_View_Helper_Pankuzu
 *
 * @param array $params
 * Params:   append: string		先頭に追加
 *           prepend: string	末尾に追加
 * </pre>
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_pankuzu( $params, &$smarty )
{
    require_once 'shared.helper_loader.php';
    $helper = smarty_helper_loader($params,$smarty);

    foreach ($params as $key => $value) {
        switch ($key) {
            case 'append':
            case 'prepend':
                $helper->Pankuzu()->$key($value);

                return ;
            default:
                break;
        }
    }

    return $helper->Pankuzu();
}
