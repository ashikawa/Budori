<?php
/**
 * Zend_Cache のファクトリクラス
 */
class Budori_Cache
{
    /**
     * @return Zend_Cache_Core|Zend_Cache_Frontend|null
     */
    public static function factory( $name )
    {
        require_once 'Zend/Registry.php';
        $manager = Zend_Registry::get('cachemanager');

        if ( !($manager instanceof Zend_Cache_Manager ) ) {
            require_once 'Zend/Cache/Exception.php';
            throw new Zend_Cache_Exception('cannot find cache manager object in register');
        }

        return $manager->getCache($name);
    }
}
