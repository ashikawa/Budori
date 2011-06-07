<?php
require_once 'Budori/Application/Bootstrap/Bootstrap.php';

class TestScript extends Budori_Application_Bootstrap_Bootstrap 
{
	public function run()
	{
		echo "hoge";
		
		$mail = new Budori_Mail();
		
		$mail->setSubject('hoge')
			->addTo('ashikawa@snappy.ne.jp')
			->setBodyText('hogehoge')
			->send();
	}
}
