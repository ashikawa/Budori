<?php
class TestController extends Zend_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->getHelper('viewRenderer')->setNoRender(true);
	}
	
	
	public function indexAction()
	{	
		echo "run scripts" . PHP_EOL;
	}
}