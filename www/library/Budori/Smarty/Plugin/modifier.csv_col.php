<?php
function smarty_modifier_csv_col( $string )
{
    $string = str_replace('"','""', $string);

    return "\"$string\"";
}
