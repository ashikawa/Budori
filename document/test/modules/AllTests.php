<?php
class Modules_AllTests
{
	public static function suite()
	{		
		$suite = new PHPUnit_Framework_TestSuite('moduel tests');
		
		require_once 'LibrariesTest.php';
		$suite->addTestSuite("LibrariesTest");
		
		return $suite;
	}
}