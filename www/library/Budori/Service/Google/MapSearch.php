<?php
require_once 'Zend/Http/Client.php';


/**
 * GoogleMap検索
 */
class Budori_Service_Google_MapSearch 
{
	
	/**
	 * Googleに要求するデータタイプ
	 * @var string
	 */
	public $output = 'json';
	
	/**
	 * リクエストURL
	 * @var string
	 */
	public static $_requestUrl = "http://maps.google.com/maps/geo";
	
	/**
	 * 通信用のオブジェクト
	 * @var Zend_Http_Client
	 */
	protected $_client;
	
	/**
	 * Enter description here...
	 * @param Zend_Http_Client $client
	 */
	public function __construct( Zend_Http_Client $client = null )
	{
		if( is_null($client) ){
			$client = new Zend_Http_Client();
		}
		
		$this->_client = $client;
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param string $query
	 * @return Zend_Http_Response
	 */
	public function search( $query )
	{
		
		$string = urlencode($query);
		
		$output	= $this->output;
		
		$url = self::$_requestUrl . "?q=$string&output=$output&sensor=false";
		
		$this->_client->setUri($url);
		
		return $this->_client->request();
	}
	
}
