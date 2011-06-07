<?php
/**
 * ファイルタイプの調査。
 * ※Zend_Mime　とは関係ない。(あっちはメール関係)
 */
class Budori_File_Mime
{
	/**
	 * file_info resource
	 * @var resource
	 */
	protected $_finfo = null;
	
	/**
	 * FILEINFO_NONE (integer)
	 * 	特別な処理を行いません。 
	 * FILEINFO_SYMLINK (integer) 
	 * 	シンボリックリンクのリンク先をたどります。 
	 * FILEINFO_MIME (integer) 
	 * 	テキスト表現ではなく、mime 文字列を返します。 
	 * FILEINFO_COMPRESS (integer) 
	 * 	圧縮されたファイルを伸張します。 
	 * FILEINFO_DEVICES (integer) 
	 * 	ブロックデバイスあるいはキャラクタデバイスの内容を探します。 
	 * FILEINFO_CONTINUE (integer) 
	 * 	最初に見つかったものだけでなく、一致するものをすべて返します。 
	 * FILEINFO_PRESERVE_ATIME (integer) 
	 * 	可能な限り、元の最終アクセス時刻を保持します。 
	 * FILEINFO_RAW (integer) 
	 * 	表示できない文字を \ooo 形式の 8 進表現に変換しません。	 
	 */
	const FINFO_OPTION	= FILEINFO_MIME;
	
	/**
	 * Enter description here...
	 */
	public function __construct()
	{}
	
	/**
	 * Enter description here...
	 *
	 * @param string $file
	 * @param integer $options
	 * @param resource $context
	 * @return string
	 */
	public function file($file, $options=FILEINFO_NONE, $context=null)
	{
		if(is_null($this->_finfo)){
			$this->open();
		}
		
		return finfo_file($this->_finfo,$file,$options,$context);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $file
	 * @param integer $options
	 * @param resource $context
	 * @return string
	 */
	public function buffer($buffer, $options=FILEINFO_NONE,$context=null)
	{
		if(is_null($this->_finfo)){
			$this->open();
		}
		
		return finfo_buffer($this->_finfo,$buffer,$options,$context);
	}
	
	/**
	 * Enter description here...
	 * @param string $magicFile
	 */
	public function open($magicFile=null)
	{
		if( is_null($magicFile) && getenv('MAGIC')){
			$magicFile = getenv('MAGIC');
		}
		if( is_null($magicFile) ){
			require_once 'Budori/Exception.php';
			throw new Budori_Exception('magic file not found');
		}
		
		$this->_finfo = finfo_open(self::FINFO_OPTION, $magicFile);
	}
	
	/**
	 * Enter description here...
	 * @return boolean
	 */
	public function close()
	{
		return finfo_close($this->_finfo);
	}
}
