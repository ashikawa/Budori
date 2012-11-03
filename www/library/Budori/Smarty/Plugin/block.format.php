<?php
/**
 * 文字列のフォーマット
 *
 * @param array
 * <pre>
 * Params:   indent: string null	インデントする文字列
 *           stripline: boolean (true)	空行の削除
 * </pre>
 */
function smarty_block_format($params, $content, &$smarty)
{
    if (is_null($content)) {
        return;
    }

    $indent		= null;
    $stripline	= true;

    foreach ($params as $_key => $_val) {
        switch ($_key) {
            case 'indent':
                $$_key = (string) $_val;
                break;
            case 'stripline':
                $$_key = (bool) $_val;
                break;
            default:
                $smarty->trigger_error("textformat: unknown attribute '$_key'");
        }
    }

    if ($stripline) {
        $content = preg_replace('/^\s*[\n\r]{1,2}/mu','',$content);
    }

    if (!is_null($indent)) {
        $content = preg_replace('/^\s*/mu', $indent, $content);
    }

    return $content;
}
