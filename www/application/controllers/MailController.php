<?php
class MailController extends Neri_Controller_Action_Http 
{
	public function indexAction()
	{
		if( !$this->getRequest()->isPost() ){
			return ;
		}
		
		$params = $this->_getAllParams();
		
		$mail = new Budori_Mail();
		
		$result = $mail->setSubject($params['subject'])
				->addTo($params['to'])
				->setFrom($params['fromaddr'], $params['fromname'])
				->setReplyTo($params['fromaddr'], $params['fromname'])
				->setBodyHtml($params['body'])
				->send();
	}
}
