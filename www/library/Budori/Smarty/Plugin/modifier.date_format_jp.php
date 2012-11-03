<?php
//require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');
require_once(SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
/**
 * Smarty date_format_jp modifer plugin
 *
 * <pre>
 * smarty_modifier_date_formatの日本語化
 * %a は日本語の曜日に変換される
 * </pre>
 *
 * {@link http://www.smarty.net/manual/ja/language.modifier.date.format.php}
 * {Smarty online manual)
 *
 * @param string $string
 * @param string $format
 * @param string $default_date
 * @return string
 *
 * @uses smarty_shared_escape_special_chars()
 * @uses smarty_shared_make_timestamp()
 * @uses smarty_function_html_options()
 */
function smarty_modifier_date_format_jp($string, $format="%b %e, %Y", $default_date=null)
{
    if (substr(PHP_OS,0,3) == 'WIN') {
           $_win_from = array ('%e',  '%T',       '%D');
           $_win_to   = array ('%#d', '%H:%M:%S', '%m/%d/%y');
           $format = str_replace($_win_from, $_win_to, $format);
    }
    if ($string != '') {
        // code added
        $_dayArray	= array('日', '月', '火', '水', '木', '金', '土');

        // 曜日を日本語表記へ変換
        if (eregi('%a', $format)) {
            $_tempDay = strftime('%w', smarty_make_timestamp($string));
            $_tempDay = $_dayArray[$_tempDay];
            if(ereg('%A', $format))	$_tempDay .= '曜日';
            $format	= eregi_replace('%a', $_tempDay, $format);
        }

        return strftime($format, smarty_make_timestamp($string));
    } elseif (isset($default_date) && $default_date != '') {
        return strftime($format, smarty_make_timestamp($default_date));
    } else {
        return;
    }
}
