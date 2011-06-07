<?php
/**
 * a href="mailto:... " タグの作成
 * 
 * @todo DOCOMO最新の機種で文字化け
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_mail( $params, &$smarty )
{
	$to		= "";
	$tmp	= array();
	
	foreach ($params as $_key => $_val) {
		switch ($_key){
			case 'to':
				$$_key = $_val;
				break;
			case 'subject':
			case 'body':
			case 'cc':
			case 'bcc':
				$tmp[$_key] = $_val;
				break;
			default:
				$smarty->trigger_error("unknown param [$_key]");
				break;
		}
	}
	
	$result = "mailto:" . urlencode($to);
	
	if(!empty($tmp)){
		$result .= '?';
		$ii = 0;
		foreach($tmp as $_k => $_v){
			if($ii++) $result .= '&';
			$result .= "$_k=" . smarty_function_mail_convert($_v);
		}
	}
	return $result;
}


function smarty_function_mail_convert($string)
{
	$string = str_replace("\r\n", "\n", $string);
	$string = str_replace("\r", "\n", $string);
	$string = str_replace("\n", "\r\n", $string);
	
	$agent	= MobileUserAgent::getInstance();
	
	$carrier = $agent->getCarrier();
	
	switch ($carrier){
		case MobileUserAgent::USER_AGENT_SOFTBANK:
			return urlencode(mb_convert_encoding($string, "UTF-8", "auto"));
			break;
		case MobileUserAgent::USER_AGENT_DOCOMO:
			return urlencode(mb_convert_encoding($string, "sjis-win", "auto"));
			break;
		default:
			return urlencode(mb_convert_encoding($string, "UTF-8", "auto"));
			break;
	}
}
