<?php

/**
 * 画像リソースの入出力、編集を管理。
 * @todo エラー処理をもっとしっかり。
 */
class Budori_Image
{
	/**
	 * 出力画像品質
	 * @var integer
	 */
	protected $_quality = 100;
	
	/**
	 * リソースオブジェクト
	 * @var Budori_Image_Resource
	 */
	protected $_resource;
	
	/**
	 * 画像タイプ
	 * @var integer
	 */
	protected $_type;
	
	/**
	 * Enter description here...
	 * @var Zend_Loader_PluginLoader_Interface
	 */
	protected static $_loader;
	
	/**
	 * 画像読み込み用関数(callback)
	 * @var array
	 */
	protected $_input	= array(
		IMAGETYPE_GIF	=> 'imagecreatefromgif',
		IMAGETYPE_JPEG	=> 'imagecreatefromjpeg',
		IMAGETYPE_PNG	=> 'imagecreatefrompng',
	);
	
	/**
	 * 画像出力用関数(callback)
	 * @var array
	 */
	protected $_output	= array(
		IMAGETYPE_GIF	=> 'imagegif',
		IMAGETYPE_JPEG	=> 'imagejpeg',
		IMAGETYPE_PNG	=> 'imagepng',
	);
	
	/**
	 * 許可する画像タイプ
	 * @var unknown_type
	 */
	protected $_arrowType = array(
		IMAGETYPE_GIF,
		IMAGETYPE_JPEG,
		IMAGETYPE_PNG,
	);
	
	/**
	 * コンストラクタ
	 * 入力値から画像タイプを判別して、リソースオブジェクトの初期化
	 * @param mix $data | string or file path
	 */
	public function __construct($data=null)
	{
		if(!is_null($data)){
			
			$image = null;
			
			require_once 'Budori/File/Mime.php';
			$mime = new Budori_File_Mime();
			
			if(is_file($data)){
				
				$mime = $mime->file($data);
				
				$type = $this->_getImageTypeFromMime($mime);
				
				if( !in_array($type,$this->_arrowType) ){
					throw new Budori_Image_Exception("wrong data type");
				}
				
				$image = $this->_loadResourceFile($data, $type);
				
			}else if(is_string($data)){
				$mime = $mime->buffer($data);
				
				$type = $this->_getImageTypeFromMime($mime);
				
				if( !in_array($type,$this->_arrowType) ){
					throw new Budori_Image_Exception("wrong data type");
				}
				
				$image = $this->_loadResourceString($data);
			}
			
			$this->_resource	= $image;
			$this->_type		= $type;
		}
		
		self::_setupLoader();
	}
	
	/**
	 * Enter description here...
	 */
	protected static function _setupLoader()
	{
		if(is_null(self::$_loader)){
			
			require_once 'Zend/Loader/PluginLoader.php';
			$loader = new Zend_Loader_PluginLoader();
			$loader->addPrefixPath('Budori_Image_Proc','Budori/Image/Proc');
			
			self::setPluginLoader($loader);
		}
	}
	
	/**
	 * Enter description here...
	 * @param Zend_Loader_PluginLoader_Interface $loader
	 */
	public static function setPluginLoader(Zend_Loader_PluginLoader_Interface $loader)
	{
		self::$_loader = $loader;
	}
	
	/**
	 * Enter description here...
	 * @return Zend_Loader_PluginLoader_Interface
	 */
	public static function getPluginLoader()
	{
		self::_setupLoader();
		return self::$_loader;
	}
	
	/**
	 * mimeタイプから、画像タイプの取得
	 * @param string $mime
	 * @return integer
	 */
	protected function _getImageTypeFromMime($mime)
	{
		switch ($mime){
			case 'image/gif':
				return IMAGETYPE_GIF;
				break;
			case 'image/jpeg':
				return IMAGETYPE_JPEG;
				break;
			case 'image/png':
				return IMAGETYPE_PNG;
				break;
			default:
				break;
		}
		return null;
	}
	
	/**
	 * ファイルからリソースオブジェクトの設定
	 * @param string $file
	 * @param sring $type
	 * @return Budori_Image_Resource
	 */
	protected function _loadResourceFile($file, $type)
	{
		$resource = call_user_func_array($this->_input[$type],array($file));
		return $this->_factory($resource);
	}
	
	/**
	 * テキスト情報からリソースオブジェクトの設定
	 * @param string $data
	 * @return Budori_Image_Resource
	 */
	protected function _loadResourceString($data)
	{
		$resource = imagecreatefromstring($data);
		return $this->_factory($resource);
	}
	
	/**
	 * リソースオブジェクトの生成
	 * @param resource $resource
	 * @return Budori_Image_Resource
	 */
	protected  function _factory($data)
	{
		require_once 'Budori/Image/Resource.php';
		return new Budori_Image_Resource($data);
	}
	
	/**
	 * リソースオブジェクトの設定
	 * @param Budori_Image_Resource $image
	 */
	public function setResource(Budori_Image_Resource $resource)
	{
		$this->_resource = $resource;
	}
	
	/**
	 * リソースオブジェクトの取得
	 * @return Budori_Image_Resource
	 */
	public function getResource()
	{
		return $this->_resource;
	}
	
	/**
	 * 画像タイプを取得
	 * @return integer
	 */
	public function getType()
	{
		return $this->_type;
	}
	
	/**
	 * 画像タイプを設定
	 * @param integer $type
	 */
	public function setType($type)
	{
		$this->_type = $type;
	}
	
	/**
	 * 画像の品質を取得
	 * @return integer
	 */
	public function getQuality()
	{
		return $this->_quality;
	}
	
	/**
	 * 出力する画像の品質を設定(0～100 jpg,png のみ)
	 * @param integer $quality
	 */
	public function setQuality($quality)
	{
		$this->_quality = $quality;
	}
	
	/**
	 * Mimeタイプの取得
	 * @return string
	 */
	public function getMime()
	{
		return image_type_to_mime_type($this->getType());
	}
	
	/**
	 * 画像の保存
	 * $stream 指定時には空文字が変える
	 * 
	 * 
	 * @param string $stream
	 * @return string
	 */
	public function saveImage( $stream = null )
	{
		$image	= $this->getResource();
		$type	= $this->getType();
		
		
		/**
		 * オプションの切り分け
		 * imagejpg の quality オプションは 0 ～ 100
		 * imagepng の quality オプションは 0 ～ 9
		 * pngのqualityは無視しておく
		 */
		switch ($type){
			case IMAGETYPE_GIF:
			case IMAGETYPE_PNG:
				$arg = array( $image->getData(), $stream );
				break;
			case IMAGETYPE_JPEG:
				$arg = array( $image->getData(), $stream, $this->getQuality() );
				break;
			default:
				require_once 'Budori/Image/Exception.php';
				throw new Budori_Image_Exception("un supported file type $type");
				break;
		}
		
		ob_start();
		call_user_func_array($this->_output[$type], $arg);
		$data = ob_get_contents();
		ob_clean();
		
		return $data;
	}
	
	
	
	
	/**
	 * @param string $name
	 * @param array $arguments
	 * @return Budori_Image_Proc
	 */
	public function callProcess($name, $arguments)
	{
		$instance = $this->getProcessClass($name);
		
		if( !method_exists($instance,$name) ){
			require_once 'Budori/Image/Exception.php';
			throw new Budori_Image_Exception("proc method '$name' not exists in '$class'");
		}
		
		$ret = call_user_func_array(array($instance,$name),$arguments);
		
		if($ret instanceof Budori_Image_Resource ){
			$this->_resource = $ret;
		}
		
		return $this;
	}
	
	
	/**
	 * magic method
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return Budori_Image_Proc
	 */
	public function __call( $name, $arguments )
	{
		return $this->callProcess($name,$arguments);
	}
	
	/**
	 * @param string $name
	 * @return Budori_Image_Proc_Abstract
	 */
	public function getProcessClass($name)
	{
		$class = self::getPluginLoader()->load($name);
		
		$instance = new $class($this->getResource());
		
		if( !($instance instanceof Budori_Image_Proc_Abstract) ){
			require_once 'Budori/Image/Exception.php';
			throw new Budori_Image_Exception('proc class not instance of Budori_Image_Proc_Abstract');
		}
		
		return $instance;
	}
}
