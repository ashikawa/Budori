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
		$this->_twitter	= new Zend_Service_Twitter($options);
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
			}catch (Exception $e){
				$this->_logout();
				throw $e;
			}
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
