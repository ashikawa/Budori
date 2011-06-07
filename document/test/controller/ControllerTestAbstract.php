<?php
require_once("Zend/Test/PHPUnit/ControllerTestCase.php");

class ControllerTestAbstract extends Zend_Test_PHPUnit_ControllerTestCase
{
	public function setUp()
	{
		parent::setUp();
		// add by ashikawa
		require_once  '../application/defines.inc';
		
		require_once 'Zend/Application.php';
		$application = new Zend_Application(
								APPLICATION_ENV,
								APP_ROOT . '/configs/application.ini'
					        );
		
		$this->bootstrap = $application->bootstrap();
	}
}