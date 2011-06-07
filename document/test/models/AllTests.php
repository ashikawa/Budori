<?php
class Models_AllTests
{
	public static function suite()
	{		
		$suite = new PHPUnit_Framework_TestSuite('moduel tests');
		
		require_once 'AccountTest.php';
		$suite->addTestSuite("AccountTest");
		
		return $suite;
	}
}