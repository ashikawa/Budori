<?php
class Budori_Image 
{
	/**
	 * Enter description here...
	 * @var Zend_Loader_PluginLoader_Interface
	 */
	protected static $_loader = null;
	
	/**
	 * @var Budori_Image_Resourde
	 */
	protected $_resource = null;
	
	
	/**
	 * コンストラクタ
	 * 入力値から画像タイプを判別して、リソースオブジェクトの初期化
	 * @param mix $data | string or file path
	 */
	public function __construct(Budori_Image_Resource $resource = null)
	{
		$this->_resource = $resource;
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
	
	public function setResource(Budori_Image_Resource $resource)
	{
		$this->_resource = $resource;
	}
	
	/**
	 * @return Budori_Image_Resource
	 */
	public function getResource()
	{
		return $this->_resource;
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
