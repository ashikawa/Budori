<?php
class Neri_Service_Mixi
{
	
	public static function getDeveloperKey()
	{
		$config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		return $config['keys']['youtube']['developer'];
	}
	
	public static function getClientKey()
	{
		$config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		return $config['keys']['youtube']['client'];
	}
}
