<?php
require_once 'Zend/View/Interface.php';

/**
 * Zend_View_Smarty
 * @see http://framework.zend.com/manual/ja/zend.view.scripts.html
 * Zend_View_Helperを使えるように拡張
 */
class Budori_View_Smarty implements Zend_View_Interface
{

    /**
     * Smarty object
     * @var Budori_Smarty
     */
    protected $_smarty;

    /**
     * Enter description here...
     * @var Budori_View_Plugin
     */
    protected $_plugin ;

    /**
     * コンストラクタ
     *
     * @param  array $extraParams
     * @return void
     *
     * @uses Budori_Util::getConfiginiFile()
     */
    public function __construct( Smarty $smarty = null )
    {

        if ( is_null($smarty) ) {
            $smarty = new Smarty();
        }

        $this->_smarty = $smarty;

        require_once 'Budori/View/Plugin.php';
        $this->_plugin = new Budori_View_Plugin($this);
    }

    /**
     * テンプレートエンジンオブジェクトを返します
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->_smarty;
    }

    /**
     * テンプレートへのパスを設定します
     *
     * @param  string $path パスとして設定するディレクトリ
     * @return void
     */
    public function setScriptPath($paths)
    {
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        $this->_smarty->setTemplateDir($paths);
        // $this->_smarty->template_dir = $paths;
        return;
    }

    /**
     * スクリプトパスの追加
     * @param string $name
     */
    public function addScriptPath($name)
    {
        if (is_readable($name)) {
            $this->_smarty->addTemplateDir($name);
            // $this->_smarty->template_dir[] = $name;
            return;
        }

        require_once 'Budori/View/Exception.php';
        throw new Budori_View_Exception("無効なパスが指定されました : '$name'");
    }

    /**
     * 現在のテンプレートディレクトリを取得します
     *
     * @return string
     */
    public function getScriptPaths()
    {
        return $this->_smarty->template_dir;
    }

    /**
     * setScriptPath へのエイリアス
     *
     * @param  string $path
     * @param  string $prefix Unused
     * @return void
     */
    public function setBasePath($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    /**
     * setScriptPath へのエイリアス
     *
     * @param  string $path
     * @param  string $prefix Unused
     * @return void
     */
    public function addBasePath($path, $prefix = 'Zend_View')
    {
        return $this->setScriptPath($path);
    }

    public function getVars()
    {
        return $this->_smarty->getTemplateVars();
    }

    /**
     * 変数をテンプレートに代入します
     *
     * @param  string $key 変数名
     * @param  mixed  $val 変数の値
     * @return void
     */
    public function __set($key, $val)
    {
        $this->assign($key, $val);
    }

    /**
     * 代入された変数を取得します
     *
     * @param  string $key 変数名
     * @return mixed  変数の値
     */
    public function __get($key)
    {
        return $this->_smarty->getTemplateVars($key);
    }

    /**
     * empty() や isset() のテストが動作するようにします
     *
     * @param  string  $key
     * @return boolean
     */
    public function __isset($key)
    {
        return (null !== $this->_smarty->getTemplateVars($key));
    }

    /**
     * オブジェクトのプロパティに対して unset() が動作するようにします
     *
     * @param  string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->_smarty->clearAssign($key);
    }

    /**
     * 変数をテンプレートに代入します
     *
     * 指定したキーを指定した値に設定します。あるいは、
     * キー => 値 形式の配列で一括設定します
     *
     * @see __set()
     * @param  string|array $spec  使用する代入方式 (キー、あるいは キー => 値 の配列)
     * @param  mixed        $value (オプション) 名前を指定して代入する場合は、ここで値を指定します
     * @return void
     */
    public function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $this->_smarty->assign($spec);

            return;
        }

        $this->_smarty->assign($spec, $value);
    }

    /**
     * 代入済みのすべての変数を削除します
     *
     * Zend_View に {@link assign()} やプロパティ
     * ({@link __get()}/{@link __set()}) で代入された変数をすべて削除します
     *
     * @return void
     */
    public function clearVars()
    {
        $this->_smarty->clearAllAssign();
    }

    /**
     * テンプレートを処理し、結果を出力します
     *
     * @param  string $name 処理するテンプレート
     * @return string 出力結果
     */
    public function render($name)
    {
        try {

            $this->_file = $name;

            ob_start();
            $this->_run($name);

            return ob_get_clean();

        } catch (SmartyException $e) {
            require_once 'Budori/View/Exception.php';
            throw new Budori_View_Exception($e->getMessage(),$e->getCode(),$e);
        }
    }

    /**
     * プレフィルターのセット
     *
     * @param string $filter
     * @param string $key
     */
    public function setPreFilter( $filter, $key )
    {
        $this->_smarty->autoload_filters['pre'][$key] = $filter;
    }

    /**
     * ポストフィルターのセット
     *
     * @param string $filter
     * @param string $key
     */
    public function setPostFilter( $filter, $key )
    {
        $this->_smarty->autoload_filters['post'][$key] = $filter;
    }

    /**
     * アウトプットフィルターのセット
     * @param string $filter
     * @param string $key
     */
    public function setOutputFilter( $filter, $key )
    {
        $this->_smarty->autoload_filters['output'][$key] = $filter;
    }

    /**
     * プラグインに処理を委譲
     *
     * @param  string $name
     * @param  mixed  $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array(
            array($this->_plugin, $name), $args
        );
    }

    public function _run()
    {
        $arg = func_get_args();

        list($resource_name,$cache_id,$compile_id) = array_pad($arg, 3, null);

        if (!is_string($resource_name)) {
            require_once 'Budori/View/Exception.php';
            throw new Budori_View_Exception('not isset resouce name');
        }

        $this->_smarty->display($resource_name,$cache_id,$compile_id);
    }

    /**
     * エンジンも複製
     * プラグイン(ヘルパー)はあえてそのまま
     */
    public function __clone()
    {
        $this->_smarty = clone $this->_smarty;
    }
}
