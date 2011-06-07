<?php
class Controller_AllTests
{
//	public static function main()
//	{		
//		PHPUnit_TextUI_TestRunner::run(self::suite());
//	}
	
	public static function suite()
	{		
		$suite = new PHPUnit_Framework_TestSuite('controller tests');
		
		require_once 'IndexControllerTest.php';
		$suite->addTestSuite("IndexControllerTest");
		
		require_once 'InfoControllerTest.php';
		$suite->addTestSuite("InfoControllerTest");
		
		require_once 'AuthControllerTest.php';
		$suite->addTestSuite("AuthControllerTest");
		
		require_once 'TwitterControllerTest.php';
		$suite->addTestSuite("TwitterControllerTest");
		
		require_once 'TestControllerTest.php';
		$suite->addTestSuite("TestControllerTest");
		
		
		return $suite;
	}
}