<?php
class FacebookController extends Neri_Controller_Action_Http 
{
	
	/**
	 * @var Facebook
	 */
	protected $_facebookSdk = null;
	
	/**
	 * @var Zend_Session_Namespace
	 */
	protected $_session	= null;
	
	
	
	public function init()
	{
		parent::init();
		
		$this->_session = new Zend_Session_Namespace("FACEBOOK_CLIENT");
		
		$this->_initSdk();
	}
	
	protected function _initSdk()
	{
		$options = Budori_Config::factory("facebook.ini","oauth")->toArray();
		
		require_once 'facebook/facebook.php';
		$facebook = new Facebook($options);
		
		$session = $this->_session;
		
//		if( isset($session->ACCESS_TOKEN) ){
//			$accessToken =  $session->ACCESS_TOKEN;
//			$facebook->setAccessToken($accessToken);
//		}
		
		$facebook->setAccessToken("182201908479030|2.AQANJAe700e-Wh8c.3600.1309244400.0-100001895370536|BAjrVME_s0MsauUr-gB4-vY52Fo");
		
		$this->_facebookSdk = $facebook;
	}
	
	public function indexAction()
	{
		$facebook = $this->_facebookSdk;
		
		$uid = $this->_facebookSdk->getUser();
		
		$result = $facebook->api(array(
						"method"	=> "fql.query",
						"query"		=> "SELECT app_id,display_name FROM application WHERE app_id IN ( SELECT application_id FROM developer WHERE developer_id = '$uid' )",
					));
		
		$this->view->assign(
				array( "result" => $result )
			);
	}
	
	
	/**
	 * call oauth and redirect
	 */
	public function authorizeAction()
	{
		$facebook	= $this->_facebookSdk;
		$scope		= array("publish_stream", "status_update");
		
		$domain		= $this->getRequest()->getServer("SERVER_NAME");
		
		$url = $facebook->getLoginUrl(
					array(
						'scope'			=> implode(",", $scope),
						'redirect_uri'	=> "http://$domain/facebook/callback",
					)
				);
		
		return $this->_redirect($url);
	}
	
	public function callbackAction()
	{
		$facebook	= $this->_facebookSdk;
		$session	= $this->_session;
		
		
		// Error!
		// Error validating verification code
		
		$accessToken =  $facebook->getAccessToken();
		$session->ACCESS_TOKEN = $accessToken;
		
		$controlelr = $this->getRequest()->getControllerName();
		return $this->_redirect("/$controlelr/");
	}
}
