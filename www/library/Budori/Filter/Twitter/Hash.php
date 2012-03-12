<?php
require_once 'Zend/Filter/Interface.php';

class Budori_Filter_Twitter_Hash implements Zend_Filter_Interface
{
	public function filter($string)
	{
		return 	preg_replace("/([\s]|^)\#([\w\-]+)/i"
						, '$1<a href="http://twitter.com/#!/search/realtime/%23$2" target="_blank">#$2</a>'
						, $string);
	}
}