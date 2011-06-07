<?php
require_once("ControllerTestAbstract.php");

class TestControllerTest extends ControllerTestAbstract
{
	public function testAllAction()
	{
//		$this->dispatch('/test/foward');
//		$this->assertResponseCode(200);
				
//		$this->dispatch('/test/foward2');
//		$this->assertResponseCode(404);
	}
	
	public function testFeedAction()
	{
		$this->dispatch('/test/feed');
		$this->assertResponseCode(200);
	}
	
	public function testMapAction()
	{
		$this->dispatch('/test/map');
		$this->assertResponseCode(200);
	}
	
	public function testLogAction()
	{
		$this->dispatch('/test/log');
		$this->assertResponseCode(200);
	}
	
	public function testAmazonAction()
	{
		$this->dispatch('/test/amazon');
		$this->assertResponseCode(200);
	}
	
	public function testYoutubeAction()
	{
		$this->dispatch('/test/youtube');
		$this->assertResponseCode(200);
	}
	
	public function testIndexAction()
	{
		$this->dispatch('/test/');
		$this->assertResponseCode(200);
	}
	
	public function testSmartyAction()
	{
		$this->dispatch('/test/smarty');
		$this->assertResponseCode(200);
	}
	
	public function testQrcodeAction()
	{
		$this->dispatch('/test/qrcode');
		$this->assertResponseCode(200);
	}
	
	public function testExceptionAction()
	{
		$this->dispatch('/test/exception');
		$this->assertResponseCode(500);
	}
	
	public function testEscapeAction()
	{
		$this->dispatch('/test/escape');
		$this->assertResponseCode(200);
	}
	
	public function mecabAction()
	{
		$this->dispatch('/test/mecab');
		$this->assertResponseCode(200);
	}
	
	public function testMailAction()
	{
		$this->dispatch('/test/mail');
		$this->assertResponseCode(200);
	}
	
	public function testTranslateAction()
	{
		$this->dispatch('/test/translate');
		$this->assertResponseCode(200);
	}
	
	public function testCookieAction()
	{
//		$this->dispatch('/test/cookie');
//		$this->assertResponseCode(200);
	}
	
	public function testCookie2Action()
	{
//		$this->dispatch('/test/cookie2');
//		$this->assertResponseCode(200);
	}
	
}
