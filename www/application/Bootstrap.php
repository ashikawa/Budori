<?php
require_once 'Budori/Application/Bootstrap/Bootstrap.php';
require_once 'Budori/Config.php';

/**
 * Bootstrapの試作
 * 最後に　return null;　を書く。
 * $this->bootstrap()->getResource()の戻り値チェックをする。
 */
class Bootstrap extends Budori_Application_Bootstrap_Bootstrap 
{
	/**
	 * Autoloaderの設定
	 * @return null
	 */
	protected function _initAutoLoader()
	{
		$this->getApplication()->getAutoloader()
				->setFallbackAutoloader(true)
				->pushAutoloader(null,"Smarty_")
				->unregisterNamespace(array('Zend','ZendX'));
		
		return null;
	}
	
	public function _initConfigDir()
	{
		Budori_Config::setBaseDir($this->getOption('configdir'));
		return null;
	}
	
	/**
	 * Validatorの日本語化
	 * abstractクラスのstaticメソッド呼び出しが凄く気持ち悪い。
	 * 
	 * @return null
	 */
	protected function _initValidate()
	{
		$this->bootstrap('ConfigDir');
		
		require_once 'Zend/Validate/Abstract.php';
		
		Zend_Validate_Abstract::setDefaultTranslator(
			new Zend_Translate(
				'array',
				Budori_Config::factory( 'validate.ini', 'Budori' )->toArray(),
				'ja'
			)
		);
		
		return null;
	}
	
	/**
	 * Zend_Db_AdapterのFetchModel設定。
	 * @return null
	 */
	protected function _initFetch()
	{
		$db = $this->bootstrap('db')->getResource('db');
		
		if( !is_null($db) ){
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
		}
		
		return null;
	}
	
	protected function _initDbProfiler()
	{
		$log = $this->bootstrap('log')->getResource('log');
		
		$profiler = new Budori_Db_Profiler(true);
		$profiler->setLogger($log);
		
		$db = $this->bootstrap('db')->getResource('db');
		$db->setProfiler($profiler);
		
		return null;
	}
	
	/**
	 * データベースの設定
	 * @return null
	 */
	protected function _initDbTable()
	{
		$cache = $this->bootstrap('cachemanager')
						->getResource('cachemanager');
		
		require_once 'Zend/Db/Table/Abstract.php';
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache->getCache('file'));
		
		return null;
	}
	
	/**
	 * Viewの初期化
	 * @return Zend_View
	 */
	protected function _initView()
	{
		
		$smarty = $this->bootstrap('smarty')->getResource('smarty');
		
		if( !is_null($smarty) ){
			$smarty->configLoad('smarty.ini');
			
			require_once 'Budori/View/Smarty.php';
			$view = new Budori_View_Smarty($smarty);
			
			$view->addHelperPath( "Budori/View/Helper", 'Budori_View_Helper_' );
			
			require_once 'Zend/Controller/Action/HelperBroker.php';
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')
						->setView( $view );
			
	        return $view;
		}
		
		return null;
	}
	/**
	 * Zend_View_Helper の初期化。
	 * ※主にスクリプトへのパスの初期値を設定したいもの。
	 */
	protected function _initViewHelper()
	{
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('helper/paginator.phtml');
		return null;
	}
	
	/**
	 * ファイルを分割したいので、ResourceRouter は使わない。
	 */
	protected function _initRooter()
	{
		$bootstrap = $this->bootstrap('FrontController')
							->bootstrap('ConfigDir');
		$this->getContainer();
		
		$config = Budori_Config::factory('router.ini');
		Zend_Controller_Front::getInstance()->getRouter()->addConfig( $config );
		
		return null;
	}
	
	/**
	 * mime ファイル
	 * @todo Zend_Validate_File_MimeType で $_ENV['MAGIC'] ってやってるからそっち統一か？
	 * Zendのほかのソースも確認する事。
	 * 
	 * @return null
	 */
	protected function _initMagicMime()
	{
		$file = $this->getOption('mimefile');
		putenv("MAGIC=$file");
		
		return null;
	}
	
	/**
	 * 独自の画像処理を追加する場合
	 */
//	protected function _initMediaLoader()
//	{
//		$loader = Budori_Image::getPluginLoader();
//		$loader->addPrefixPath('Neri_Image_Proc','Neri/Image/Proc');
//		
//		Budori_Image::setPluginLoader($loader);
//	}
	
	/**
	 * URLの名前解決ルールを変更する場合。
	 * '_'が嫌な時とか。
	 */
//	protected function _initDispatcher()
//	{
//		$front = Zend_Controller_Front::getInstance();
//		
//		$dispatcher = new Zend_Controller_Dispatcher_Standard();
//		$dispatcher->setPathDelimiter('-')
//					->setWordDelimiter(array('.'));
//		
//		$front->setDispatcher($dispatcher);
//	}
}
