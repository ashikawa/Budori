<?php
/** @see PHPUnit_Runner_Version */
require_once 'PHPUnit/Runner/Version.php';
/**
 * Depending on version, include the proper PHPUnit support
 * @see PHPUnit_Autoload
 */
require_once (version_compare(PHPUnit_Runner_Version::id(), '3.5.0', '>=')) ? 'PHPUnit/Autoload.php' : 'PHPUnit/Framework.php';

class LibrariesTest extends PHPUnit_Framework_TestCase
{	
	public function testSmartyVersion()
	{
		require_once 'Smarty.class.php';
		// 'Smarty-3.0.7'
		$vsersion		= explode('-', Smarty::SMARTY_VERSION);
		$this->assertLessThanOrEqual(0, version_compare('3.0.0', $vsersion[1]));
	}
	
	public function testZendVersion()
	{
		require_once 'Zend/Version.php';
		$this->assertLessThanOrEqual(0, Zend_Version::compareVersion('1.11.0'));
	}
}
