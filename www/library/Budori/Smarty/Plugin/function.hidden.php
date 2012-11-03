<?php
/**
 * パラメーター(省略で$_POST)からhiddenタグを作成
 * 配列は二次元まで。
 *
 * @todo 二次元までで良いのか？
 * @todo Zend_Form と連携を考える。(今のところ予定ナシ)
 *
 * @param    array $params
 * <pre>
 * Params:   param: array	出力する配列(省略時は$_POST)
 *           token: string	トランザクショントークン(Budori_TransactionToken)のキー
 * </pre>
 * @param    Smarty $smarty
 * @return   string
 */
function smarty_function_hidden($params, &$smarty)
{
    $param = array();
    $token = null;

    foreach ($params as $_key => $_value) {
        switch ($_key) {
            case 'param';
                $param = (array) $_value;
                break;
            case 'token':
                $token = $_value;
                break;
            default:
                $smarty->trigger_error("unknown params [$_key]");
                //$param[$_key] = $_value;
                break;
        }
    }

    if (!is_null($token)) {
        if ( !($token instanceof Budori_TransactionToken) ) {
            $smarty->trigger_error('token is not instance of TransactionToken');
        }
        $param[$token->getPostName()] = $token->getToken();
    }

    $result = "";

    foreach ($param as $_key => $_value) {
        if ( is_array($_value) ) {

            foreach ($_value as $_k => $_v) {
                if (is_string($_k)) {
                    $result .= smarty_function_hidden_gethtml("$_key" . "[$_k]", $_v);
                } else {
                    $result .= smarty_function_hidden_gethtml("$_key"."[]", $_v);
                }
            }

        } else {
            $result .= smarty_function_hidden_gethtml($_key, $_value);
        }
    }

    return $result;
}

function smarty_function_hidden_gethtml( $name, $value )
{
    return  "<input type=\"hidden\" name=\"$name\" value=\"$value\" />" . PHP_EOL;
}

return ;
/**
 * 以下cake 用に作ったやつ。再起バージョン
 * @todo 作業中
 * @author ashikawa
 */
function generateHidden( $data, $holder = "data" )
{
    if ($data instanceof Model) {
        $data = array("data" => $data->data);
    }

    if (!is_array($data)) {
        trigger_error("Wrong paramater type for " . __METHOD__, E_USER_WARNING);

        return "";
    }

    return $this->_generate( "", $holder, $data);
}

function _generate($resultHtml, $name, $value)
{
    if ( is_array($value) ) {

        $generated = "";

        foreach ($value as $_key => $_value) {

            if ( is_null($name) || $name  == "") {
                $_name = h($_key);
            } else {
                if ( is_string($_key) ) {
                    $_name = "[" . h($_key) . "]";
                } else {
                    $_name = "[$_key]";
                }
            }
            $generated .= $this->_generate($resultHtml, $name . $_name, $_value);
        }

        return $resultHtml . $generated;
    } else {
        $value = h($value);

        return "<input type=\"hidden\" name=\"$name\" value=\"$value\" />" . PHP_EOL;
    }
}
