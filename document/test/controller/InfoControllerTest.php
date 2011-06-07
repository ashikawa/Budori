<?php
require_once("ControllerTestAbstract.php");

class InfoControllerTest extends ControllerTestAbstract
{
	public function testAllAction()
	{
		$this->dispatch('/info/');
		$this->assertResponseCode(200);
		
		$this->dispatch('/info/phpinfo');
		$this->assertResponseCode(200);
	}
	
	public function testMogeAction()
	{
		$this->dispatch('/info/moge');
		$this->assertResponseCode(404);
	}
	
	public function test404Action()
	{
		$this->dispatch('/info/hogehoge');
		$this->assertResponseCode(404);
	}
	
	public function testGdinfoAction()
	{
//		$this->dispatch('/info/gdinfo');
//		$this->assertResponseCode(200);
	}
}
