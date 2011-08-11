<?php
require_once("Zend/Test/PHPUnit/ControllerTestCase.php");

class ControllerTestAbstract extends Zend_Test_PHPUnit_ControllerTestCase
{
	public function setUp()
	{
		parent::setUp();
		
		require_once 'Zend/Application.php';
		$application = new Zend_Application(
								APPLICATION_ENV,
								APP_ROOT . '/configs/application.ini'
					        );
		
		$this->bootstrap = $application->bootstrap();
	}
}