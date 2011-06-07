<?php
/**
 * Zend_View_Abstractからプラグイン関係の処理を抜き出した物。
 * ほぼコピペ
 * ※　一部バージョンが古い可能性あり
 */
class Budori_View_Plugin 
{
	
    /**
     * View Object
     * @var Zend_View_Interface
     */
    public $view;
	
    /**
     * Plugin types
     * @var array
     */
    private $_loaderTypes = array('filter', 'helper');
	
    /**
     * Plugin loaders
     * @var array
     */
    private $_loaders = array();
	
    
    /**
     * Helpers 
     * @var array
     */
    private $_helper = array();
    
	/**
	 * constructor
	 * @param Zend_View_Interface $view
	 */
    public function __construct(Zend_View_Interface $view)
    {
    	$this->view = $view;
    	$this->view->getEngine()->_plugins['helper'] = $this;
    }
    
    /**
     * Retrieve a plugin object
     *
     * @param  string $type
     * @param  string $name
     * @return object
     */
    private function _getPlugin($type, $name)
    {
        $name = ucfirst($name);
        switch ($type) {
            case 'filter':
                $storeVar = '_filterClass';
                $store    = $this->_filterClass;
                break;
            case 'helper':
                $storeVar = '_helper';
                $store    = $this->_helper;
                break;
        }

        if (!isset($store[$name])) {
            $class = $this->getPluginLoader($type)->load($name);
            $store[$name] = new $class();
            if (method_exists($store[$name], 'setView')) {
                $store[$name]->setView( $this->view );
            }
        }

        $this->$storeVar = $store;
        return $store[$name];
    }
    
    /**
     * Retrieve plugin loader for a specific plugin type
     *
     * @param  string $type
     * @return Zend_Loader_PluginLoader
     */
    public function getPluginLoader($type)
    {
        $type = strtolower($type);
        if (!in_array($type, $this->_loaderTypes)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception(sprintf('Invalid plugin loader type "%s"; cannot retrieve', $type));
        }
		
        if (!array_key_exists($type, $this->_loaders)) {
            $prefix     = 'Zend_View_';
            $pathPrefix = 'Zend/View/';
			
            require_once 'Zend/Loader/PluginLoader.php';
            
            $pType = ucfirst($type);
            switch ($type) {
                case 'filter':
                case 'helper':
                default:
                    $prefix     .= $pType;
                    $pathPrefix .= $pType;
                    $loader = new Zend_Loader_PluginLoader(array(
                        $prefix => $pathPrefix
                    ));
                    $this->_loaders[$type] = $loader;
                    break;
            }
        }
        return $this->_loaders[$type];
    }
	
    /**
     * Get a path to a given plugin class of a given type
     *
     * @param  string $type
     * @param  string $name
     * @return string|false
     */
    private function _getPluginPath($type, $name)
    {
        $loader = $this->getPluginLoader($type);
        if ($loader->isLoaded($name)) {
            return $loader->getClassPath($name);
        }

        try {
            $loader->load($name);
            return $loader->getClassPath($name);
        } catch (Zend_Loader_Exception $e) {
            return false;
        }
    }
    
    /**
     * Get a helper by name
     *
     * @param  string $name
     * @return object
     */
    public function getHelper($name)
    {
        return $this->_getPlugin('helper', $name);
    }

    /**
     * Get array of all active helpers
     *
     * Only returns those that have already been instantiated.
     *
     * @return array
     */
    public function getHelpers()
    {
        return $this->_helper;
    }
    
    /**
     * Get full path to a helper class file specified by $name
     *
     * @param  string $name
     * @return string|false False on failure, path on success
     */
    public function getHelperPath($name)
    {
        return $this->_getPluginPath('helper', $name);
    }
    
    /**
     * Add a prefixPath for a plugin type
     *
     * @param  string $type
     * @param  string $classPrefix
     * @param  array $paths
     * @return Zend_View_Abstract
     */
    private function _addPluginPath($type, $classPrefix, array $paths)
    {
        $loader = $this->getPluginLoader($type);
        foreach ($paths as $path) {
            $loader->addPrefixPath($classPrefix, $path);
        }
        return $this;
    }
    
    /**
     * Adds to the stack of helper paths in LIFO order.
     *
     * @param string|array The directory (-ies) to add.
     * @param string $classPrefix Class prefix to use with classes in this
     * directory; defaults to Zend_View_Helper
     * @return Zend_View_Abstract
     */
    public function addHelperPath($path, $classPrefix = 'Zend_View_Helper_')
    {
        return $this->_addPluginPath('helper', $classPrefix, (array) $path);
    }
    
    /**
     * エスケープ処理
     * 
     * @param mixed $val
     * @return mixed
     */
	public function escape( $val )
	{
		require_once 'Budori/Util/String.php';
		return Budori_Util_String::escape($val);
	}
	
    /**
     * Accesses a helper object from within a script.
     *
     * If the helper class has a 'view' property, sets it with the current view
     * object.
     * 
     * @param string $name The helper name.
     * @param array $args The parameters for the helper.
     * @return string The result of the helper output.
     */
    public function __call($name, $args)
    {
        // is the helper already loaded?
        $helper = $this->getHelper($name);

        // call the helper method
        return call_user_func_array(
            array($helper, $name),
            $args
        );
    }
    
}