<?php
/**
 * パラメータのキーにが '_' で始まっていればGETのオプションに、
 * そうでなければ,Zend_Controller_Router::assemble()の第一パラメータになる
 * 
 * @param array $params
 * Params:	reset: boolean		ディフォルトのルーティングを使用する
 * 			encode: boolean		urlエンコード
 * 			name: string ルーティング名
 * 			assign: 出力を格納する変数
 * 			other: ....
 * </pre>
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_url( $params, &$smarty )
{
    require_once('shared.helper_loader.php');
	$helper = smarty_helper_loader($params,$smarty);
   
	$urlOptions = array();
	$args		= $_GET;
	$name		= null;
	$reset		= false;
	$encode		= true;
	$assign		= null;
	
    foreach ($params as $_key=>$_value) {
        switch ($_key) {
        	case 'reset':
        	case 'encode':
        		$$_key = $_value ? true : false;
        		break;
        	case 'name':
        	case 'assign':
        		$$_key = (string) $_value;
        		break;
            default:
            	if(substr($_key,0,1) == '_'){
	            	$args[substr($_key,1)] = $_value;
            	}else{
            		$urlOptions[$_key] = $_value;
            	}
            	break;
        }
    }
    
    $url = $helper->url( $urlOptions, $name, $reset, $encode);
    
    if( !empty($args) ){
    	$url	= $url . '?' . http_build_query($args);
	}
	
	if(!is_null($assign)){
		$smarty->assign($assign,$url);
	}else{
		return $url;
	}
}
