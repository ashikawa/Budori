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
		
		$token = $consumer->getToken();
		
		if( is_null( $token ) ){
			return;
		}
		
		$url		= "https://www.googleapis.com";
		
		$service	= new Zend_Rest_Client($url);
		
		$service->getHttpClient()->setHeaders("Authorization", "OAuth " . $token->access_token);
		
		
		$response = $service->restGet("/plus/v1/people/me");
		
		if( $response->getStatus() != 200){
			throw new Zend_Service_Exception( $response->getMessage(), $response->getStatus() );
		}
		
		$this->view->assign(array(
			'me'	=> json_decode($response->getBody(), true),
		));
	}
	
	
	/**
	 * Google は RequestToken 使わないらしいので、普通に組み立ててリダイレクトでOK
	 */
	public function authorizeAction()
	{
		$options = array(
			"scope"	=> "https://www.googleapis.com/auth/plus.me",
		);
		
		$url	= $this->_consumer->getRedirectUrl( $options );
		return $this->_redirect( $url );
	}
	
	/**
	 * @todo makeRequest
	 * 著名とかは何故か callback で行う
	 * FB とかとは著名とユーザー認証の順番が入れ替わる。　何故？
	 */
	public function callbackAction()
	{
		$consumer	= $this->_consumer;
		$session	= $this->_session;
		
		$session->OAUTH_TOKEN = $consumer->requestToken( $this->_getAllParams() );
		
		$this->_forward("index");
	}
	
	
	public function refreshAction()
	{
		$session	= $this->_session;
		$consumer	= $this->_consumer;
		
		$session->OAUTH_TOKEN = $consumer->refreshToken();
		
		$this->_forward("index");
	}
	
	
	public function logoutAction()
	{
		$session = $this->_session;
		$session->unsetAll();
		
		$this->_forward("index");
	}
}
