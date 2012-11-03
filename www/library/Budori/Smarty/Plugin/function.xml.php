<?php
/**
 * Smarty {xml} function plugin
 *
 * ブラウザを判別してXML宣言を動的に出力するプラグイン
 *
 * Input:
 *         - version string
 *         - encoding string
 *
 * Example:
 *  {xml version="1.0" encoding="utf-8"}
 *
 * @param    array　$params
 * @param    Smarty $smarty
 * @return   string
 */
function smarty_function_xml( $params, &$smarty )
{

    $version	= null;
    $encoding	= null;
    $standalone = null;

    foreach ($params as $key => $value) {

        switch ($key) {
            case 'version':
            case 'encoding':
            case 'standalone':
                $$key = $value;
                break;
            default:
                break;
        }
    }

    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

    $_html_result = "";

    if (!(ereg("Windows",$ua) && ereg("MSIE",$ua)) || !ereg("MSIE 6",$ua)) {

        $_html_result = "<?xml";

        if( !is_null( $version ) )
            $_html_result.= " version=\"$version\"";
        if( !is_null($encoding) )
            $_html_result.= " encoding=\"$encoding\"";
        if( !is_null($standalone) )
            $_html_result.= " standalone=\"$standalone\"";

        $_html_result.= "?>";
    }

    return $_html_result;
}
