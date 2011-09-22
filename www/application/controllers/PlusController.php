<?php


class PlusController extends Neri_Controller_Action_Http
{
	
	const CLIENT_ID		= 'xxxxxxxxxxxxxxxxxx';
	
	const CLIENT_SECRET	= 'yyyyyyyyyyyyyyyyyy';
	
	const CALLBACK_URL	= 'http://budori.ashikawa.com/plus/callback';
	
	/**
	 * @var apiClient
	 */
	protected $_client	= null;
	
	/**
	 * @var apiPlusService
	 */
	protected $_plus	= null;
	
	/**
	 * @var 
	 */
	protected $_session	= null;
	
	public function init()
	{
		parent::init();
		
		// for dev
		Zend_Session::setOptions(array("cookie_domain" => ".ashikawa.com"));
		
		
		require_once 'google-api-php-client/src/apiClient.php';
		
		$client = new apiClient();
		
		$client->setApplicationName("Google+ PHP Starter Application");
		$client->setClientId( self::CLIENT_ID );
		$client->setClientSecret( self::CLIENT_SECRET );
		$client->setRedirectUri( self::CALLBACK_URL );
		
		$this->_client = $client;
		
		
		
		$session = new Zend_Session_Namespace("GOOGLE_PLUS");
		
		if( isset($session->OAUTH_TOKEN) ){
			$client->setAccessToken($session->OAUTH_TOKEN);
		}		
		$this->_session = $session;
		
		
		
		require_once 'google-api-php-client/src/contrib/apiPlusService.php';
		
		$plus = new apiPlusService($client);
		
		$this->_plus = $plus;
	}
	
	public function indexAction()
	{
		$client = $this->_client;
		
		if( $client->getAccessToken() ){
			
			$plus = $this->_plus;
			$optParams = array('maxResults' => 100);
			
			$this->view->assign(array(
				"me"			=> $plus->people->get('me'),
				"activities"	=> $plus->activities->listActivities('me', 'public', $optParams),
			));
		}
	}
	
	public function authorizeAction()
	{
		$client	= $this->_client;
		
		$client->authenticate();
		exit;
	}
	
	public function callbackAction()
	{
		$session	= $this->_session;
		$client		= $this->_client;
		
		$session->OAUTH_TOKEN = $client->authenticate();
		
		$this->_redirect("/plus/");
	}
	
	
//	/**
//	 * @var Budori_Service_Google_Plus
//	 */
//	protected $_service = null;
//	
//	public function init()
//	{
//		parent::init();
//		
//		$options = array(
//			"requestScheme"		=> Zend_Oauth::REQUEST_SCHEME_HEADER,
//			"signatureMethod"	=> "HMAC-SHA1",
//			"authorizeUrl"		=> "https://accounts.google.com/o/oauth2/auth",
//			"siteUrl"			=> "https://accounts.google.com/o/oauth2/auth",
//			"accessTokenUrl"	=> "https://accounts.google.com/o/oauth2/token",
//			"callbackUrl"		=> self::CALLBACK_URL,
//			"consumerKey"		=> self::CLIENT_ID,
//			"consumerSecret"	=> self::CLIENT_SECRET,
//		);
//		
//		$this->_service = new Budori_Service_Google_Plus( $options );
//	}
//	
//	/**
//	 * call oauth and redirect
//	 */
//	public function authorizeAction()
//	{
//		$service = $this->_service;
//		
//		if( !$service->isAuthorised() ){
//			$requestToken	= $service->getRequestToken();
//			$this->_session->request_token	= $requestToken;
//			
//			//return $twitter->redirect();
//			$url = $service->getRedirectUrl();
//			return $this->_redirect($url);
//		}
//		
//		return $this->_forward('index');
//	}
	
}
