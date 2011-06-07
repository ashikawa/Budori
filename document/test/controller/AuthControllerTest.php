<?php
require_once("ControllerTestAbstract.php");

class AuthControllerTest extends ControllerTestAbstract
{
	public function testIndexAction()
	{
		$this->dispatch('/auth/');
		$this->assertResponseCode(200);
	}
	
	public function testLoginAction()
	{
		//ログイン失敗 auth/index へフォワード
		
		$this->request->setPost(
				array(
					'id'		=> 'XXXXXX',
					'password'	=> 'YYYYYY',
				));
		
        $this->request->setMethod('POST');
		$this->dispatch('/auth/login');
		
		$this->assertResponseCode(200);
		
		$this->assertController("auth");
		$this->assertAction("index");
	}
}
