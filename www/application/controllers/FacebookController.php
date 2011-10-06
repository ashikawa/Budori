<?php
/**
 * http://fierce-winter-3467.herokuapp.com/
 * @author shigeru.ashikawa
 *
 */
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
		
		if( isset($session->ACCESS_TOKEN) ){
			$accessToken =  $session->ACCESS_TOKEN;
			$facebook->setAccessToken($accessToken);
			
			Budori_Log::factory()->debug("token: " . $accessToken);
		}
				
		$this->_facebookSdk = $facebook;
	}
	
	public function indexAction()
	{
		$facebook = $this->_facebookSdk;
		
		$user = $this->_facebookSdk->getUser();
		
		var_export($user);
		
//		$result = $facebook->api(array(
//						"method"	=> "fql.query",
//						"query"		=> "SELECT app_id,display_name FROM application WHERE app_id IN ( SELECT application_id FROM developer WHERE developer_id = '$uid' )",
//					));
//		
//		$this->view->assign(
//				array( "result" => $result )
//			);
	}
	
	public function friendsAction()
	{
		$this->disableLayout();
		$this->setNoRender();
		$this->getResponse()->setHeader("Content-Type", "text/plain");
		
		
		$facebook = $this->_facebookSdk;
		
		var_dump($facebook->api("/me/friends"));
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
						'redirect_uri'	=> "http://budori.ashikawa.vm/facebook/callback",
					)
				);
		
		return $this->_redirect($url);
	}
	
	public function callbackAction()
	{
		$facebook	= $this->_facebookSdk;
		$session	= $this->_session;
		
		//　{
		//　	"error": {
		//　		"type": "OAuthException",
		//　		"message": "Error validating verification code."
		//　	}
		//　}
		// currentUrl と redirectUrl が違っていると発生するらしい。
		
		$accessToken =  $facebook->getAccessToken();
		$session->ACCESS_TOKEN = $accessToken;
		
		
		Budori_Log::factory()->debug("token: " . $accessToken);
		
		$controlelr = $this->getRequest()->getControllerName();
		
		return $this->_redirect("/$controlelr/");
	}
	
	
	/**
	 * post message for wall
	 */
	public function postAction()
	{
		$facebook = $this->_facebookSdk;
		
		if( $facebook->getUser()
				&& $this->_getParam("value") ){
			
			try {
				$result	= $model->statusUpdate( $this->_getParam("value") );
			}catch (Exception $e){
				$this->_logout();
				throw $e;
			}
		}
	}
	
	
	public function logoutAction()
	{
		$this->_logout();
		$this->_redirect("/facebook/");
	}
	
	/**
	 * remove oauth session
	 */
	protected function _logout()
	{
		$this->_facebookSdk->destroySession();
		$this->_session->unsetAll();
	}
}
