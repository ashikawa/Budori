<?php
require_once 'modifier.link.php';
/**
 * @todo Tweet
 * 			:: toString() ...
 * 			:: getImage() ...
 * @todo filter parse_url parse_atname parse_hash
 */
function smarty_modifier_tweet( $string )
{
	$string = Budori_Util_String::escape($string);
	
//	短縮URL
	if( preg_match_all('/http:\/\/t.co\/(\w+)/i', $string, $matches) ){
			
		foreach ($matches[0] as $_val){
			
			$pattern	= '/'.preg_quote($_val,'/').'/';
			$replace	= smarty_modifier_short_url($_val);
			$string		= preg_replace( $pattern, $replace, $string );
		}
	}
	
	$imageService = array();
	if( preg_match_all("/https?:\/\/[-_.!~*'\(\)[[:alnum:];\/?:@&=+$,%#]+/i", $string, $matches) ){
		
		foreach ($matches[0] as $_url){
			if(preg_match('/twitpic.com\/([^\s"]+)/i', $string, $tmp)){
				$imageService[]	= "http://twitpic.com/show/thumb/".$tmp[1];
			}else if(preg_match('/plixi.com\/p\/([^\s"]+)/i', $string, $tmp)){
				$imageService[]	= "http://api.plixi.com/api/TPAPI.svc/imagefromurl?size=thumbnail&url=".urlencode("http://plixi.com/p/".$tmp[1]);
			}else if(preg_match('/yfrog.com\/([^\s"]+)/i', $string, $tmp)){
				$imageService[]	= "http://yfrog.com/".$tmp[1].":small";
			}
		}
	}
	
	$string = smarty_modifier_link($string);
	
	$string = preg_replace("/([\s]|^)\@([\w\-]+)/i", '$1<a href="http://twitter.com/$2" target="_blank">@$2</a>', $string);
	$string = preg_replace("/([\s]|^)\#([\w\-]+)/i", '$1<a href="http://twitter.com/search?q=%23$2" target="_blank">@$2</a>', $string);
	
	foreach ($imageService as $_val){
		$string .= "<img src=\"$_val\" />";
	}
	return $string;
}

function smarty_modifier_short_url( $string )
{
	$headers = get_headers($string, 1);
	
	if(isset($headers['Location'])){
		
		if( is_array($headers['Location']) ){
			return array_pop($headers['Location']); // last element
		}else{
			return $headers['Location'];
		}
	}else{
		return $string;
	}
}