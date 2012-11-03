<?php

/**
 * Zend_Config　のファクトリクラス
 * BootStrap等から、探索するディレクトリを指定できるよう拡張。
 *
 */
class Budori_Config
{

    protected static $_basedir = array();

    public static function addBaseDir( $dir )
    {
        array_push(self::$_basedir,(string) $dir);
    }

    public static function setBaseDir($dir)
    {
        self::$_basedir = (array) $dir;
    }

    /**
     * 拡張子を判別して Zend_Config クラスのインスタンスを返す
     * @return Zend_Config
     */
    public static function factory($filename, $section = null, $options = false)
    {

        $path = "";

        if ( !empty(self::$_basedir) ) {

            $path = get_include_path();

            $newPath = '.';
            foreach (self::$_basedir as $_v) {
                $newPath .= PATH_SEPARATOR . realpath($_v);
            }
            $newPath .= PATH_SEPARATOR . $path;

            set_include_path($newPath);
        }

        $config = self::_generateConfig( $filename, $section, $options );

        if ($path !== "") {
            set_include_path($path);
        }

        return $config;
    }

    /**
     * 拡張子と、Zend_Configクラスの対応メソッド
     *
     * @param  string      $filename
     * @param  string      $section
     * @param  array       $options
     * @return Zend_Config
     */
    protected static function _generateConfig( $filename, $section = null, $options = false )
    {
        require_once 'Zend/Config/Exception.php';
        $file = pathinfo( $filename );

        // return あるけど break もちゃんと書いておけ。
        switch (strtolower($file['extension'])) {
            case 'ini':
                require_once 'Zend/Config/Ini.php';

                return new Zend_Config_Ini( $filename, $section, $options );
                break;
            case 'xml':
                require_once 'Zend/Config/Xml.php';

                return new Zend_Config_Xml( $filename, $section, $options );
                break;
            case 'php':
            case 'inc':
                $config = include $filename;
                if (!is_array($config)) {
                    throw new Zend_Config_Exception('Invalid configuration file provided; PHP file does not return array value');
                }
                $allowModifications = false;
                if (isset($options['allowModifications'])) {
                    $allowModifications = (bool) $options['allowModifications'];
                }
                require_once 'Zend/Config.php';

                return new Zend_Config($config,$allowModifications);
                break;
            default:
                break;
        }

        throw new Zend_Config_Exception("extension must be '.ini' '.xml' '.php' '.inc'");
    }
}
