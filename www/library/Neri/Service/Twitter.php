<?php

class Neri_Service_Twitter
{
	
	public static function getId()
	{
		$config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		return $config['keys']['twitter']['id'];
	}
	
	public static function getPass()
	{
		$config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		return $config['keys']['twitter']['pass'];
	}
}
