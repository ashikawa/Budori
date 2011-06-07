<?php
require_once("ControllerTestAbstract.php");

class IndexControllerTest extends ControllerTestAbstract
{
	public function testIndexAction()
	{
		$this->dispatch('/');
		$this->assertResponseCode(200);
	}
	
	public function testRobotsAction()
	{
		$this->dispatch('/robots.txt');
		$this->assertResponseCode(200);
	}
}
