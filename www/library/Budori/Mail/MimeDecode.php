<?php
/**
 * Pear Mail_mimeDecode のデコーダー
 */
class Budori_Mail_MimeDecode 
{
	
	/**
	 * コンテンツタイプ　マルチパート( / の左側 )
	 */
	const CONTENT_MULTIPART		=	'multipart';
	
	/**
	 * メールヘッダーの配列
	 * @var array
	 */
	protected $_header;
	
	
	/**
	 * 本文の配列
	 * @var Budori_Mail_MimeDecode_Content
	 */
	protected $_bodyContent;
	
	
	/**
	 * 添付ファイルの配列
	 * @var array
	 * array(
	 * 	0 => Budori_Mail_MimeDecode_Content
	 *  1 => ...
	 * )
	 */
	protected $_attachment;
	
	
	/**
	 * Header の From からメールアドレスを抜き出す
	 * 
	 * 要調査
	 * 
	 * DoCoMoのRFC違反のメール
	 * http://dualkey.saloon.jp/blog3/?p=97
	 * "hogehoge"@docomo.co.jp
	 * 
	 * DoCoMo・Ezweb はメールアドレスの前後に<>がつかない
	 * http://d.hatena.ne.jp/slywalker/20080227/1204111394
	 * 
	 * @var string
	 */
	protected $_emailRegex = "/([_\w\.\-\"]+@[_0-9a-zA-Z\.\-]+\.[a-zA-Z]+)/";
	
	
	/**
	 * Mail_mimeDecode　に渡すパラメータ
	 * @var array
	 */
	protected $_decodeParam = array(
			'include_bodies'	=> true,
			'decode_bodies'		=> true,
			'decode_headers'	=> true,
		);
	
	/**
	 * メール本文として扱うコンテンツタイプ
	 * @var array
	 */
	protected $_arrowContents = array(
		'text/plain'
	);
		
	
	/**
	 * コンストラクタ
	 * Pear::Mail_mimeDecode　のインスタンスを渡す
	 * @param Mail_mimeDecode $mimeDecode
	 */
	public function __construct( Mail_mimeDecode $mimeDecode )
	{
		
		$decode = $mimeDecode->decode( $this->_decodeParam );
		
		$this->_header = $decode->headers;
		
		if( $decode->ctype_primary == self::CONTENT_MULTIPART ){
			$this->_multiPart( $decode );
		}else{
			$this->_singlePart( $decode );
		}
	}
	
	
	/**
	 * HeaderのFromからメールアドレスを抜き出す
	 * @return sytring
	 */
	public function getFromAddress()
	{
		$from = $this->_header['from'];
		if( preg_match($this->_emailRegex, $from, $match) === false )
		 	throw new Exception('preg_error');
		 	
		return $match[1];
	}
	
	
	/**
	 * ファイルが添付されているか調べる
	 * @return boolean
	 */
	public function hasAttachment()
	{
		return ( count($this->_attachment) != 0 );
	}
	
	
	/**
	 * メール本文を取得
	 * @return Budori_Mail_MimeDecode_Content | null
	 */
	public function getBodyContent()
	{
		return $this->_bodyContent;
	}
	
	
	/**
	 * 添付ファイルを全て取得
	 * @return array
	 */
	public function getAttachment()
	{
		return $this->_attachment;
	}
	
	
	/**
	 * マルチパートの初期化処理
	 * @param stdClass $decode	Mail_mimeDecode::decode()の結果
	 */
	protected function _multiPart( $decode )
	{
		$cArray = array();
		
		foreach ( $decode->parts as $_value ){
			
			require_once 'Budori/Mail/MimeDecode/Content.php';
			$cArray[] = new Budori_Mail_MimeDecode_Content( $_value );
		}
		
		$top = array_shift($cArray);
		if( in_array( $top->getContentType(), $this->_arrowContents ) ){
			
			$this->_bodyContent		= $top;
		}else{
			$this->_attachment[]	= $top;
		}
		
		$this->_attachment	= $cArray;
	}
	
	
	/**
	 * 一般的なメールの初期化処理
	 * @param stdClass $decode	Mail_mimeDecode::decode()の結果
	 */
	protected function _singlePart( $decode )
	{
		$content = new Budori_Mail_MimeDecode_Content( $decode );
		
		if( in_array( $content->getContentType(), $this->_arrowContents ) ){
			$this->_bodyContent		= $content;
		}else{
			$this->_attachment[]	= $content;
		}
	}
}
