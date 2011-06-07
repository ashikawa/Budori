<?php
/**
 * 次のバージョンのZend で　Zend_File_Transfer が正式に動くようになったら廃止
 */
class Budori_Upload 
{
	/**
	 * tmpファイルオブジェクト
	 * @var SplFileInfo
	 */
	public $tmpFile;
	
	/**
	 * アップロード後ファイルオブジェクト
	 * @var SplFileInfo
	 */
	public $File;
	
	/**
	 * $_FILE['****']
	 * @var array
	 */
	protected $_file;
	
	/**
	 * エラーコード
	 * @var string
	 */
	protected $code;
	
	/**
	 * エラーメッセージ一覧
	 * @var array
	 */
	protected $_messages = array(
		'0'		=> 'UPLOAD_ERR_OK',
		'1'		=> 'UPLOAD_ERR_INI_SIZE',
		'2'		=> 'UPLOAD_ERR_FORM_SIZE',
		'3'		=> 'UPLOAD_ERR_PARTIAL',
		'4'		=> 'UPLOAD_ERR_NO_FILE',
		'5'		=> 'UPLOAD_ERR_NO_TMP_DIR',
		'6'		=> 'UPLOAD_ERR_CANT_WRITE',
		'7'		=> 'UPLOAD_ERR_EXTENSION',
	);
	
	
	/**
	 * ファイル変数
	 * @param string $file  $_FILE['****']のキー
	 * @throws Zend_Exception
	 */
	public function __construct( $name = null )
	{
		if( !is_null($name) ){
			$file = $_FILES[$name];
		}else{
			$file = current($_FILES);
		}
		
		if( $file == null ){
			require_once 'Budori/Upload/Exception.php';
			throw new Budori_Upload_Exception('not found upload file');
		}
		
		$this->_file = $file;
		
		$this->code = $file['error'];
	}
	
	/**
	 * エラーコードを返す
	 * @return integer
	 */
	public function getCode()
	{
		return $this->_file['error'];
	}
	
	/**
	 * ファイルサイズの取得
	 * @return string
	 */
	public function getSize()
	{
		return $this->_file['size'];
	}
	
	/**
	 * mimeタイプの取得
	 * @return string
	 */
	public function getType()
	{
		return $this->_file['type'];
	}
	
	/**
	 * ファイル名の取得
	 * @return sring
	 */
	public function getName()
	{
		return $this->_file['name'];
	}
	
	/**
	 * メッセージの取得
	 * @return string
	 */
	public function getMessage()
	{
		return $this->_messages[$this->code];
	}
	
	/**
	 * 一次保存ファイルの取得
	 * @return SplFileInfo
	 */
	public function getTmpFile()
	{
		if( $this->tmpFile == null ){
			$this->tmpFile = new SplFileInfo($this->_file["tmp_name"]);
		}
		
		return $this->tmpFile;
	}
	
	/**
	 * アップロード後ファイルの取得
	 * @return SplFileInfo
	 */
	public function getFile()
	{
		return $this->File;
	}
	
	/**
	 * アップロード
	 * @param string $newFile
	 * @param boolean $overWrite
	 * @return SplFileInfo
	 */
	public function moveUploadedFile( $newFile, $overWrite=true )
	{
		require_once 'Budori/Upload/Exception.php';
		
		if( !$overWrite && file_exists($newFile) ){
			throw new Budori_Upload_Exception("$newFile exists");
		}
		
		$result = @move_uploaded_file( $this->_file["tmp_name"], $newFile );
			
		if ( $result === false ){
			throw new Budori_Upload_Exception('upload failer');
		}
		
		$this->File = new SplFileInfo( $this->_file );
		
		return $this;
	}
	
}
