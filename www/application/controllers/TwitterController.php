<?php
class TwitterController extends Neri_Controller_Action_Http 
{
	const TEST_STYLE_SHEET_PATH = '/style/content/twitter.css';
	
	/**
	 * @var Zend_Service_Twitter
	 */
	protected $_twitter = null;
	
	/**
	 * @var Zend_Session_Namespace
	 */
	protected $_session = null;
	
	/**
	 * controller init
	 * 	set style sheet
	 *  init session (to use oauth)
	 *  init Twitter Object
	 */
	public function init()
	{
		parent::init();
		
		$this->appendHeadLink(self::TEST_STYLE_SHEET_PATH);
		
		$this->_session	= new Zend_Session_Namespace("TWITTER_CLIENT");
		
		$this->_initTwitter();
	}
	
	/**
	 * init Twitter authorized
	 */
	protected function _initTwitter()
	{
		$options = Budori_Config::factory('twitter.ini', 'oauth')->toArray();
		
		if( isset( $this->_session->access_token ) ){
			$options['accessToken'] = $this->_session->access_token;
		}
		
//		$this->_twitter	= new Zend_Service_Twitter($options);
		$this->_twitter	= new Budori_Service_Twitter($options);
	}
	
	/**
	 * pre dispatch
	 * 	assign twitter object
	 */
	public function preDispatch()
	{
		parent::preDispatch();
		
		$this->prependTitle('twitter');
		$this->appendPankuzu('twitter','/' . $this->getRequest()->getControllerName() );
		
		$this->view->assign('client',$this->_twitter);
	}
	
	/**
	 * index action
	 * 	input & status friends timeline page
	 */
	public function indexAction()
	{
		$twitter	= $this->_twitter;
		$timeline	= null;
		
		if( $twitter->isAuthorised() ){
			$response	= $twitter->status->statusFriendsTimeline();
			$timeline	= $response->getIterator();
		}
			$this->view->assign("timeline", $timeline);
	}
	
	
	public function friendsAction()
	{
		$this->disableLayout();
		$this->setNoRender();
		$this->getResponse()->setHeader("Content-Type", "text/plain");
		
		
		$twitter	= $this->_twitter;
		
		var_dump($twitter->userFriends());
	}
	
	/**
	 * post message for user timeline
	 */
	public function postAction()
	{
		$twitter = $this->_twitter;
		
		if( $twitter->isAuthorised() ){
			// post message
			try {
				$response = $twitter->status->update( $this->_getParam("comment") );
				
				var_export($twitter->getLocalHttpClient()->getLastRequest());
				exit;
				
			}catch (Exception $e){
				$this->_logout();
				throw $e;
			}
		}
		
		return $this->_forward('index');
	}
	
	
	public function upload2Action()
	{
		require 'tmhOAuth/tmhOAuth.php';
		require 'tmhOAuth/tmhUtilities.php';
		
		/**
		 * @var Zend_Oauth_Token_Access $accessToken 
		 */
		$accessToken = $this->_session->access_token;
		
		$tmhOAuth = new tmhOAuth(array(
			'consumer_key'		=> '1Fju1DFa9mwBzXGYjDjlA',
			'consumer_secret'	=> 'YaHait8vDgLWtTvd3mfnZf0KS99jivlcl7zwcwDM8wk',
			'user_token'		=> $accessToken->getToken(),
			'user_secret'		=> $accessToken->getTokenSecret(),
		));
		
		$image	= "/tmp/20095212000019.jpg";
		
		$code	= $tmhOAuth->request(
			'POST',
			'https://upload.twitter.com/1/statuses/update_with_media.json',
			array(
				'media[]'	=> "@{$image};type=image/jpeg;filename=20095212000019.jpg",
				'status'	=> 'Picture time',
			),
			true,	// use auth
			true	// multipart
		);
		
		if( $code == 200 ){
			tmhUtilities::pr( json_decode($tmhOAuth->response['response']) );
		}else{
			tmhUtilities::pr( $tmhOAuth->response['response'] );
		}
		
		
		
		header("Content-Type: text/plain");
		exit;
		return $this->_forward('index');
	}
	
	
	public function uploadAction()
	{
		$twitter = $this->_twitter;
		//https://upload.twitter.com/1/statuses/update_with_media.json
		
		
		if( $twitter->isAuthorised() ){
			
			// for develop
			$this->disableLayout();
			$this->setNoRender();
			
			
			$status		= "test!!";
			$file		= "/tmp/20095212000019.jpg";
			
			$response	= $twitter->statuses->updateWithMedia($status, $file);
			
			
			header("Content-Type: text/plain");
			var_export( $twitter->getUri()->getUri() );
			echo PHP_EOL . PHP_EOL;
			var_export( $twitter->getLocalHttpClient()->getLastRequest() );
			echo PHP_EOL . PHP_EOL . "=====response=====" . PHP_EOL . PHP_EOL;
			
			var_export($response);
			exit;
		}
		
		return $this->_forward('index');
	}
	
	/**
	 * call oauth and redirect
	 */
	public function authorizeAction()
	{
		$twitter = $this->_twitter;
		
		if( !$twitter->isAuthorised() ){
			$requestToken	= $twitter->getRequestToken();
			$this->_session->request_token	= $requestToken;
			
			//return $twitter->redirect();
			$url = $twitter->getRedirectUrl();
			return $this->_redirect($url);
		}
		
		return $this->_forward('index');
	}
	
	/**
	 * oauth callback url
	 */
	public function callbackAction()
	{
		$twitter = $this->_twitter;
		
		if( isset( $this->_session->request_token ) ){
			
			$accessToken = $twitter->getAccessToken(
					$this->_getAllParams(),
					$this->_session->request_token );
			
			$this->_session->access_token = $accessToken;
			
			unset($this->_session->request_token);
		}
		
		$controlelr = $this->getRequest()->getControllerName();
		return $this->_redirect("/$controlelr/");
	}
	
	/**
	 * remove oauth session
	 */
	public function logoutAction()
	{
		$twitter = $this->_twitter;
		
		if( $twitter->isAuthorised() ){
			$this->_logout();
		}
		
		$controlelr = $this->getRequest()->getControllerName();
		return $this->_redirect("/$controlelr/");
	}
	
	public function searchAction()
	{
		$clowler	= new TwitterClowler();
		$result		= $clowler->search();
		
		var_dump($result);
		
		$this->view->assign('result', new Budori_Service_Twitter_TimeLine($result) );
	}
	
	protected function _logout()
	{
		$this->_session->unsetAll();
	}
}
