<?php
/**
 * Smarty {zend_layout} function plugin
 *
 * Input:
 * 		- call					id prefix
 * 		-
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_zend_layout( $params, &$smarty )
{
    require_once 'Zend/Layout.php';
    if ( is_null($ZendLayout = Zend_Layout::getMvcInstance()) ) {
        $ZendLayout = new Zend_Layout();
    }

    if (!empty($params)) {
        foreach ($params as $_key => $_val) {
            switch ($_key) {
                case 'call':
                    return $ZendLayout->$_val;
                    break;
                default:
                    $ZendLayout->$_key = $_val;
                    break;
            }
        }
    } else {
        return $ZendLayout->{$ZendLayout->getContentKey()};
    }
}
