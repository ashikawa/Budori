<?php
require_once 'Zend/Uri.php';
require_once 'Zend/Oauth.php';
require_once 'Zend/Oauth/Exception.php';

/**
 * OAuth Consumer for OAuth2.0
 * @author shigeru.ashikawa
 */
class Budori_Oauth_Consumer extends Zend_Oauth 
{
	
	const GRANT_TYPE_AUTHORIZATION_CODE	= "authorization_code";
	
	const GRANT_TYPE_REFRESH_TOKEN		= "refresh_token";
	
	const RESPONSE_TYPE_CODE			= "code";
	
	/**
	 * @var Budori_Oauth_Token
	 */
	protected $_token = null;
	
	/**
	 * @var array
	 */
	protected $_options = array();
	
	/**
	 * @param array $options
	 * 	array(
	 * 		'client_id'		=> client_id
	 *		'client_secret'	=> secret_id
	 *		'auth_uri'		=> auth redirect uri,
	 *		'token_uri'		=> get token uri,
	 *		'redirect_uri'	=> callback uri,
	 *		'token'			=> Budori_Oauth_Token (optional)
	 *	)
	 */
	public function __construct($options)
	{
		if( isset($options['token']) ){
			$this->_token = $options['token'];
			unset( $options['token'] );
		}
		
		$this->_options = $options;
	}
	
	/**
	 * redirect and exit
	 * @param $options
	 */
	public function redirect( $options )
	{
		$redirectUrl	= $this->getRedirectUrl( $options );
		header('Location: ' . $redirectUrl);
		exit(1);
	}
	
	/**
	 * assemble redirect url
	 * @param array $options
	 * 	array(
	 * 		'scope'	=> require auth scope
	 * 	)
	 */
	public function getRedirectUrl( $options )
	{
		$opt = $this->_options;
		
		$uri = Zend_Uri::factory( $opt['auth_uri'] );
		
		$options = array_merge( array(
			"response_type"	=> self::RESPONSE_TYPE_CODE,
			"redirect_uri" 	=> $opt['redirect_uri'],
 			"client_id"		=> $opt['client_id'],
		), $options);
		
		$uri->setQuery( $options );
		
		return $uri;
	}
	
	/**
	 * @param Budori_Oauth_Token $token
	 */
	public function setToken(Budori_Oauth_Token $token)
	{
		$this->_token = $token;
	}
	
	/**
	 * @return Budori_Oauth_Token
	 */
	public function getToken()
	{
		return $this->_token;
	}
	
	/**
	 * get or request OAuth token
	 * @param array $params
	 * @return Budori_Oauth_Token
	 */
	public function requestToken( $params = array() )
	{
		if( !is_null( $this->_token ) ){
			
			$_token = $this->_token;
			
			if( $_token->getCreated() + $_token->getExpiresIn() < time() ){
				
				$this->refreshToken();
			}
			return $this->_token;
		}
		
		$params = array_merge(array(
			'grant_type'	=> self::GRANT_TYPE_AUTHORIZATION_CODE,
			'redirect_uri'	=> $this->_options['redirect_uri'],
		), $params);
		
		return  $this->_requestToken( $params );
	}
	
	
	
	/**
	 * reflesh oauth token
	 * @return Budori_Oauth_Token
	 */
	public function refreshToken()
	{
		$token = $this->_token;
		
		$postParams = array(
			'refresh_token'	=> $token->refresh_token,
			'grant_type'	=> self::GRANT_TYPE_REFRESH_TOKEN,
		);
		
		$reflesh = $this->_requestToken($postParams);
		
		$token->setParams($reflesh);
		
		$this->_token = $token;
		
		return $token;
	}
	
	
	/**
	 * @param array $postParams
	 * @return Budori_Oauth_Token
	 */
	protected function _requestToken($postParams)
	{
		$client	= self::getHttpClient();
		
		$opt = $this->_options;
		
		$postParams	= array_merge(
			array(
				'client_id'		=> $opt['client_id'],
				'client_secret'	=> $opt['client_secret'],
			), $postParams );
		
		$client->setUri( $opt['token_uri'])
				->setParameterPost($postParams);
		
		$created	= time();
		
		$response	= $client->request( Zend_Oauth::POST );
		
		if( $response->getStatus() != 200 ){
			throw new Zend_Oauth_Exception( $response->getMessage(), $response->getStatus() );
		}
		
		$token = new Budori_Oauth_Token($response);
		$token->setCreated($created);
		
		return $token;
	}
}