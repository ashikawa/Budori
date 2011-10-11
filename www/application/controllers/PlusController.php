<?php
/**
 * 公式ライブラリの移植
 * @author shigeru.ashikawa
 */
class PlusController extends Neri_Controller_Action_Http
{
	/**
	 * @var Zend_Session_Namespace
	 */
	protected $_session	= null;
	
	/**
	 * @var Budori_Oauth_Consumer
	 */
	protected $_consumer = null;
	
	
	public function init()
	{
		parent::init();
		
		$this->_initSession();
		$this->_initConsumer();
	}
	
	protected function _initSession()
	{
		// for devel
		Zend_Session::setOptions(array("cookie_domain" => ".ashikawa.com"));
		$this->_session		= new Zend_Session_Namespace("GOOGLE_PLUS");
	}
	
	protected function _initConsumer()
	{
		$options = Budori_Config::factory("plus.ini", "oauth")->toArray();
		
		$session = $this->_session;
		
		if( !is_null($session->OAUTH_TOKEN) ){
			$options['token'] = $session->OAUTH_TOKEN;
		}
		
		$this->_consumer	= new Budori_Oauth_Consumer($options);
	}
	
	
	public function indexAction()
	{
		$consumer = $this->_consumer;
		
		if( is_null( $consumer->getToken() ) ){
			return;
		}
		
		$service	= new Budori_Service_Google_Plus($consumer);
		
		$this->view->assign(array(
			'me'			=> $service->get("people/me"),
			'activities'	=> $service->get("people/me/activities/public"),
//			'searchActivities'	=>	$service->get("activities", $params),
		));
	}
	
	/**
	 * Google は RequestToken 使わないらしいので、普通に組み立ててリダイレクトでOK
	 */
	public function authorizeAction()
	{
		$url	= $this->_consumer->getRedirectUrl();
		return $this->_redirect( $url );
	}
	
	public function callbackAction()
	{
		$consumer	= $this->_consumer;
		$session	= $this->_session;
		
		$params = $this->_getAllParams();
		
		$session->OAUTH_TOKEN = $consumer->requestToken( $params );
		
		$this->_redirect("/plus/");
	}
	
	public function refreshAction()
	{
		$session	= $this->_session;
		$consumer	= $this->_consumer;
		
		$session->OAUTH_TOKEN = $consumer->refreshToken();
		
		$this->_redirect("/plus/");
	}
	
	public function logoutAction()
	{
		$this->_session->unsetAll();
		
		$this->_redirect("/plus/");
	}
	
	
	
	protected function _debugMode()
	{
		$this->disableLayout();
		$this->setNoRender();
		$this->getResponse()->setHeader("Content-Type", "text/plain");
	}
}
