<?php
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * bootstrap resources for Smarty
 * 
 * 基本的な設定は Smarty の public member と同じ名前のメンバーで上書きする。
 * その他)
 * 各フィルタ(配列)
 * 	postfilter
 * 	prefilter
 *	outputfilter
 * 
 * プラグイン検索のディレクトリ(配列)
 *	plugins_dir
 * 
 * @uses Budori_Smarty
 */
class Budori_Application_Resource_Smarty extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * @var Smarty
	 */
    protected $_smarty = null;
	
    public function init()
    {
        return $this->_initSmarty();
    }
	
    protected function _loadSmarty()
    {
		require_once 'Budori/Smarty.php';
		return new Budori_Smarty();
    }
    
    protected function _initSmarty()
    {
        if (null === $this->_smarty) {
        	
			$smarty = $this->_loadSmarty();
			$config = $this->getOptions();
			
			foreach ( $config as $_key => $_val ){
				switch ($_key){
					case 'template_dir':
						$smarty->addTemplateDir($_val);
						break;
					case 'postfilter':
						foreach ($_val as $_k => $_v){
							$smarty->loadFilter('post', $_v);
						}
						break;
					case 'prefilter':
						foreach ($_val as $_k => $_v){
							$smarty->loadFilter('pre', $_v);
						}
						break;
					case 'outputfilter':
						foreach ($_val as $_k => $_v){
							$smarty->loadFilter('output', $_v);
						}
						break;
					case 'plugins_dir':
						$smarty->addPluginsDir($_val);
						break;
					default:
						$smarty->$_key = $_val;
						break;
				}
			}
			
            $this->_smarty = $smarty;
        }
        
        return $this->_smarty;
    }
}
