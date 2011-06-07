<?php

/**
 * mixi に日記を投稿する
 * 
 * @todo 作成中
 * たぶん、ハッシュ化したパスワードを保存しておくのが妥当。
 */
class Budori_Service_Mixi 
{
	protected $_user;
	
	protected $_pass;
	
	protected $_id;
	
	/**
	 * @var Zend_Http_Client
	 */
	protected $_client;
	
	
	public function __construct( $user, $pass, $id )
	{
		
		require_once 'Zend/Http/Client.php';
		$this->_client = new Zend_Http_Client();
		
		$this->setUser($user);
		$this->setPass($pass);
		$this->setId($id);
	}
	
	public function setUser( $user )
	{
		$this->_user = $user;
		return $this;
	}
	
	public function setPass( $pass )
	{
		$this->_pass = $pass;
		return $this;
	}
	
	public function setId( $id )
	{
		$this->_id = $id;
		return $this;
	}
	
	/**
	 * @return Zend_Http_Response
	 */
	public function send($title, $body)
	{
		$user			= $this->_user;
		$pass			= $this->_pass;
		
		// WSSE Authentication
		$nonce			= pack('H*', sha1(md5(time().rand())));
		$created		= date('Y-m-d\TH:i:s\Z');
		$digest			= base64_encode(pack('H*', sha1($nonce . $created . $pass)));
		$wsseText		= 'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"';
		
		$wsseHeader	= sprintf($wsseText, $user, $digest, base64_encode($nonce), $created);
		
		$url			= $this->_getRequestUri();
		
		$client			= $this->_client;
		
		$client->setUri($url)
				->setMethod(Zend_Http_Client::POST)
				->setHeaders('X-WSSE', $wsseHeader)
				->setRawData($this->_getBodyXml($title,$body), 'text/xml');
		
		return $client->request();
	}
		
	protected function _getRequestUri()
	{
		return 'http://mixi.jp/atom/diary/member_id=' . $this->_id;
	}
	
	protected function _getBodyXml($title,$body)
	{
		require_once 'Budori/Util/String.php';
		
		$title	= Budori_Util_String::escape($title);
		$body	= Budori_Util_String::escape($body);
		
		return "<entry xmlns='http://www.w3.org/2007/app'>"
			.		"<title>$title</title>"
			.		"<summary>$body</summary>"
			.	"</entry>";
	}
}
