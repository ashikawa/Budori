<?php
/**
 * 公式ライブラリの移植
 * @author shigeru.ashikawa
 */
class PlusController extends Neri_Controller_Action_Http
{
	
	const CLIENT_ID		= '682295594430.apps.googleusercontent.com';
	
	const CLIENT_SECRET	= 'IqgT_ZLr4CmmY3wW9Xn-2GgJ';
	
	const REDIRECT_URI	= 'http://budori.ashikawa.com/plus/callback';
	
	
	protected $_scope	= array("https://www.googleapis.com/auth/plus.me");
	
	
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
		// for devel
		Zend_Session::setOptions(array("cookie_domain" => ".ashikawa.com"));
		
		
		$session = new Zend_Session_Namespace("GOOGLE_PLUS");
		
		$options = array(
			'client_id'		=> self::CLIENT_ID,
			'client_secret'	=> self::CLIENT_SECRET,
			'auth_uri'		=> 'https://accounts.google.com/o/oauth2/auth',
			'token_uri'		=> 'https://accounts.google.com/o/oauth2/token',
			'redirect_uri'	=> self::REDIRECT_URI,
		);
		
		if( !is_null($session->OAUTH_TOKEN) ){
			$options['token'] = $session->OAUTH_TOKEN;
		}
		
		$this->_session		= $session;
		
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
		));
	}
	
	/**
	 * Google は RequestToken 使わないらしいので、普通に組み立ててリダイレクトでOK
	 */
	public function authorizeAction()
	{
		$options = array(
			"scope"	=> implode(" ", $this->_scope),
		);
		
		$url	= $this->_consumer->getRedirectUrl( $options );
		return $this->_redirect( $url );
	}
	
	public function callbackAction()
	{
		$consumer	= $this->_consumer;
		$session	= $this->_session;
		
		$params = array(
			'code'	=> $this->_getParam("code"),
		);
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
}
