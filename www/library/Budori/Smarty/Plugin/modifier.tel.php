<?php
/**
 * 電話番号をリンクに直す
 * @param string $string
 * @return string
 */
function smarty_modifier_tel( $string )
{
    $tel = mb_ereg_replace('[^0-9]', '', $string);

    return "<a href=\"tel:$tel\">$string</a>";
}
