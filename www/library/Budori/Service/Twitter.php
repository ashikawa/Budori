<?php 

class Budori_Service_Twitter extends Zend_Service_Twitter
{
	
	/**
	 * Types of API methods
	 *
	 * @var array
	 */
	protected $_methodTypes = array(
		'status',
		'statuses',
		'user',
		'directMessage',
		'friendship',
		'account',
		'favorite',
		'block'
	);
	
	/**
	 * parent::_post();
	 * 	-> _prepare() 内でメソッドのパラメータを削除しているため、添付ができない。
	 * 
	 * @param $status
	 */
	public function statusesUpdateWithMedia($status, $file)
	{
		
		$this->setUri("https://upload.twitter.com");
		
		$path	= '/1/statuses/update_with_media.xml';
		
		$this->_prepare($path);
		
		$response	= $this->_multipart($status,$file);
//		$response	= $this->_postData($status,$file);
//		$response	= $this->_rawData($status,$file);
		
		return new Zend_Rest_Client_Result( $response->getBody() );
	}
	
	
	/**
	 * ※　status をつけるとエラー
	 * 
	 * @param unknown_type $status
	 * @param unknown_type $file
	 */
	protected function _multipart($status, $file)
	{
		$data	= array(
			'status'	=> $status,
		);
		
		$this->getLocalHttpClient()
				->setFileUpload($file, "media[]", null, "image/jpeg");
		
		return $this->_performPost(Zend_Http_Client::POST, $data);
	}
	
	/**
	 * ※ バイナリデータの変換がおかしいらしい。
	 * 
	 * @param unknown_type $status
	 * @param unknown_type $file
	 */
	protected function _postData($status, $file)
	{
		$data = array(
			"status"	=> $status,
			"media[]"	=> file_get_contents($file),
		);
		
		$this->getLocalHttpClient()
				->setHeaders("Content-Type", "multipart/form-data");
		
		return $this->_performPost(Zend_Http_Client::POST, $data);
	}
	
	/**
	 * @todo tmhOAuthからの移植 作業中
	 * 
	 * @param unknown_type $status
	 * @param unknown_type $file
	 */
	protected function _rawData($status, $file)
	{		
		$this->getLocalHttpClient()
//				->setFileUpload("20095212000019.jpg","media[]", file_get_contents($file)."&status=hoge")
//				->setHeaders("Content-Type", "multipart/form-data")
//				->setRawData("media%5B%5D=" . file_get_contents($file) . ";type=image/jpeg;filename=20095212000019.jpg");

				->setRawData(
					$this->_safeEncode("media[]") . "="
					. file_get_contents($file)
					. ";type=image/jpeg;filename=20095212000019.jpg"
//				, "multipart/form-data"
				)
		;
		// status ...
		
		return $this->_performPost(Zend_Http_Client::POST);
	}
	
	protected function _safeEncode($data)
	{
		if (is_array($data)) {
			return array_map( array($this, '_safeEncode'), $data );
		}
		
		if (is_scalar($data)) {
			return str_ireplace(
					array('+', '%7E'),
					array(' ', '~'),
					rawurlencode($data)
				);
		}
		
		return '';
	}
	
	
	
}