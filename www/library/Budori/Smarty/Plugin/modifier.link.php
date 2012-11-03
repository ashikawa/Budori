<?php
/**
 * Smarty link modifer plugin
 *
 * 第一パラメータ中からダブルクオート、シングルクオートで始まっていないURLとメールアドレスを探し、リンクに直す。
 *
 * @param string $string
 * @return string
 *
 * @uses smarty_modifier_link_callback()
 */
function smarty_modifier_link( $string )
{
    static $filter;
    if (!$filter) {
        $filter = new Budori_Filter_Html_Link();
    }

    return $filter->filter($string);
}
