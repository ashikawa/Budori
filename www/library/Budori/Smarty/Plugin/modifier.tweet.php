<?php
require_once 'modifier.my_escape.php';
/**
 * @todo Tweet
 * 			:: toString() ...
 * 			:: getImage() ...
 * @todo filter parse_url parse_atname parse_hash
 */
function smarty_modifier_tweet( $string )
{
    $string = smarty_modifier_my_escape($string );

//	短縮URL
    if ( preg_match_all('/http:\/\/t.co\/(\w+)/i', $string, $matches) ) {

        foreach ($matches[0] as $_val) {

            $pattern	= '/'.preg_quote($_val,'/').'/';
            $replace	= smarty_modifier_short_url($_val);
            $string		= preg_replace( $pattern, $replace, $string );
        }
    }

    $imageService = array();
    if ( preg_match_all("/https?:\/\/[-_.!~*'\(\)[[:alnum:];\/?:@&=+$,%#]+/i", $string, $matches) ) {

        foreach ($matches[0] as $_url) {
            if (preg_match('/twitpic.com\/([^\s"]+)/i', $string, $tmp)) {
                $imageService[]	= "http://twitpic.com/show/thumb/".$tmp[1];
            } elseif (preg_match('/plixi.com\/p\/([^\s"]+)/i', $string, $tmp)) {
                $imageService[]	= "http://api.plixi.com/api/TPAPI.svc/imagefromurl?size=thumbnail&url=".urlencode("http://plixi.com/p/".$tmp[1]);
            } elseif (preg_match('/yfrog.com\/([^\s"]+)/i', $string, $tmp)) {
                $imageService[]	= "http://yfrog.com/".$tmp[1].":small";
            }
        }
    }

    static $filter;

    if (!$filter) {

        $filter = new Zend_Filter();

        $filter->addFilter(new Budori_Filter_Html_Link())
            ->addFilter(new Budori_Filter_Twitter_Hash())
            ->addFilter(new Budori_Filter_Twitter_user());
    }
    $string = $filter->filter($string);

    foreach ($imageService as $_val) {
        $string .= "<img src=\"$_val\" />";
    }

    return $string;
}

function smarty_modifier_short_url( $string )
{
    $headers = get_headers($string, 1);

    if (isset($headers['Location'])) {

        if ( is_array($headers['Location']) ) {
            return array_pop($headers['Location']); // last element
        } else {
            return $headers['Location'];
        }
    } else {
        return $string;
    }
}
