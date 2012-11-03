<?php
/**
 * encode filter
 *
 * @param string $source
 * @param Smarty $smarty
 */
function smarty_outputfilter_encode_sjis($source, $smarty)
{
    return mb_convert_encoding($source, "SJIS", "auto");
}
