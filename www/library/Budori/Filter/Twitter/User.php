<?php
class Budori_Filter_Twitter_User implements Zend_Filter_Interface
{
    public function filter($string)
    {
        return 	preg_replace("/([\s]|^)\@([\w\-]+)/i"
                    , '$1<a href="http://twitter.com/$2" target="_blank">@$2</a>'
                    , $string);
    }
}
