<?php
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Facebook ヘルパー
 *
 * @author shigeru.ashikawa
 * @copyright Copyright (c) 2013, infobahn inc.
 */
class Budori_Controller_Action_Helper_Facebook extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * @var array
	 */
	protected $_options = array();
	
	/**
	 * @var Budori_Service_Facebook
	 */
	private $_client = null;

	/**
	 * get initialize Options
	 */
	public function getOptions()
	{
		return $this->getFacebook()
			->getConfig();
	}
	
	/**
	 * Facebook isAuthorized
	 * @return string|number|unknown|mixied
	 */
	public function isAuthorized()
	{
		$facebook = $this->getClient();
		return $facebook->getUser();
	}

	/**
	 * OAuth Authorize & Redirect
	 * @param string $redirectUrl
	 */
	public function authorize($redirectUrl)
	{
		$facebook	= $this->getClient();
		$config	    = $facebook->getConfig();

		$scheme     = $this->getRequest()->isSecure() ? "https" : "http";
		$domain     = $this->getRequest()->getServer("SERVER_NAME");

		$url = $facebook->getLoginUrl(array(
			'scope'			=> $config['scope'],
			'redirect_uri'	=> "$scheme://$domain$redirectUrl",
		));

		$this->getActionController()
        	->getHelper('redirector')
	        ->gotoUrl($url, array());
	}

	/**
	 * OAuth Callback
	 * @throws FacebookApiException
	 * @return boolean
	 */
	public function callback()
	{
		$request = $this->getRequest();
		$params  = $request->getParams();
		$error   = $request->getParam("error");

		if (!is_null($error)) {

			if ( $params("error_reason") != "user_denied" ) {
				$message = $request->getParam("error_description", "unknown error");
				throw new FacebookApiException($message);
			}
			return false;
		}
		return true;
	}

	/**
	 * AccessToken の延長
	 */
	public function keyExchange()
	{
		$client = $this->getClient();
		$client->setExtendedAccessToken();
	}
	
	/**
	 * User Logout
	 */
	public function logout()
	{
		$this->getClient()->destroySession();
	}
	
	/**
	 * @return Budori_Service_Facebook
	 */
	public function getClient()
	{
		if (is_null($this->_client)) {
			$this->_client = $this->_buildFacebook();
		}

		return $this->_client;
	}

	/**
	 * @return Budori_Service_Facebook
	 */
	protected function _buildFacebook()
	{
		$options = $this->_loadOptions();
		return new Budori_Service_Facebook($options);
	}

	/**
	 *  load Option From Zend_BootStrap
	 * @return array
	 */
	protected function _loadOptions()
	{
		$options  = $this->getFrontController()
			->getParam("bootstrap")
			->getOption("facebook");
	
		return $options;
	}
}
