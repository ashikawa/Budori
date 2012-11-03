<?php
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * bootstrap resources for PluginLoader
 *
 * resources.pluginloader
 * 	.enableChache	プラグインローダー(Zend_Loader_PluginLoader) のキャッシュの有効、無効、
 * 	.chachedir	キャッシュを生成するディレクトリ
 */
class Budori_Application_Resource_Pluginloader extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        $config = $this->getOptions();

        $classFileIncCache = $config['chachedir'];

        if (file_exists($classFileIncCache)) {
            include_once $classFileIncCache;
        }

        if ($config['enableChache']) {
            require_once 'Zend/Loader/PluginLoader.php';

            Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
        }

        return null;
    }

}
