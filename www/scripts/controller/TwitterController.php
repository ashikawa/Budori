<?php
class TwitterController extends Zend_Controller_Action
{
	/**
	 * @var Zend_Service_Twitter
	 */
	protected $_twitter = null;
	
	/**
	 * controller init
	 * 	set style sheet
	 *  init session (to use oauth)
	 *  init Twitter Object
	 */
	public function init()
	{
		parent::init();
		$this->_initTwitter();
	}
	
	/**
	 * init Twitter authorized
	 */
	protected function _initTwitter()
	{
		$options = Budori_Config::factory('twitter.ini', 'oauth')->toArray();
		
//		if( isset( $this->_session->access_token ) ){
//			$options['accessToken'] = $this->_session->access_token;
//		}
		
//		$this->_twitter	= new Zend_Service_Twitter($options);
		$this->_twitter	= new Budori_Service_Twitter($options);
	}
	

	
	public function upload2Action()
	{
		require 'tmhOAuth/tmhOAuth.php';
		require 'tmhOAuth/tmhUtilities.php';
		
		/**
		 * @var Zend_Oauth_Token_Access $accessToken 
		 */
//		$accessToken = $this->_session->access_token;
		
		$tmhOAuth = new tmhOAuth(array(
			'consumer_key'		=> '1Fju1DFa9mwBzXGYjDjlA',
			'consumer_secret'	=> 'YaHait8vDgLWtTvd3mfnZf0KS99jivlcl7zwcwDM8wk',
//			'user_token'		=> $accessToken->getToken(),
			'user_token'		=> "83804246-17egE8F8q1sAqJ8hzOfok117T4iMyNiQbAwMJMf8",
//			'user_secret'		=> $accessToken->getTokenSecret(),
			'user_secret'		=> "3wPkOh6ddE0hTrEkJ5CyeQQka2O1fCEo9ta072w2To"
		));
		
		$image	= ROOT . "/data/img/18.jpg";
		
		$code	= $tmhOAuth->request(
			'POST',
			'http://upload.twitter.com/1/statuses/update_with_media.json',
			array(
				'media[]'	=> "@{$image};type=image/jpeg;filename=20095212000019.jpg",
				'status'	=> 'Super Penguin Time!(test)',
			),
			true,	// use auth
			true	// multipart
		);
		
		if( $code == 200 ){
			tmhUtilities::pr( json_decode($tmhOAuth->response['response']) );
		}else{
			tmhUtilities::pr( $tmhOAuth->response['response'] );
		}
	}
}
