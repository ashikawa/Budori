<?php
/**
 * Smarty {doctype} function plugin
 * ブラウザを判別して DOCTYPE を動的に出力するプラグイン
 *
 * @param    array　$params
 * @param    Smarty $smarty
 * @return   string
 */
function smarty_function_doctype($params, &$smarty)
{
    if (isset($_SERVER['HTTP_USER_AGENT'])) {

        $ua = $_SERVER['HTTP_USER_AGENT'];

        if (ereg("^DoCoMo", $ua)) {
            return '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/1.1) 1.0//EN" "i-xhtml_4ja_10.dtd">' . PHP_EOL;

        } elseif (ereg("^J-PHONE|^Vodafone|^SoftBank", $ua)) {
            return '<!DOCTYPE html PUBLIC "-//J-PHONE//DTD XHTML Basic 1.0Plus//EN" "xhtml-basic10-plus.dtd">' . PHP_EOL;

        } elseif (ereg("^UP.Browser|^KDDI", $ua)) {
            return '<!DOCTYPE html PUBLIC "-//OPENWAVE//DTD XHTML 1.0//EN" "http://www.openwave.com/DTD/xhtml-basic.dtd">' . PHP_EOL;
        }
    }

    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . PHP_EOL;
}
