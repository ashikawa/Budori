<?php
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @todo リファクタリング & エラー処理をちゃんと
 * 毎回初期化するのでAction_Helperにまとめた。
 */
class Neri_Controller_Action_Helper_Pankuzu extends Zend_Controller_Action_Helper_Abstract 
{
	
	public function preDispatch()
	{
		parent::preDispatch();
		
		$actionController = $this->getActionController();
		
		$HeadTitle = $actionController->view->getHelper('HeadTitle');
		
		if(is_null($HeadTitle)){
			require_once 'Budori/Exception.php';
			throw new Budori_Exception("cannot find View Helper 'HeadTitle'");
		}
		
		require_once 'Budori/Config.php';
		$config = Budori_Config::factory('smarty.ini');
		$HeadTitle->setSeparator(' | ')->set($config->TITLE);
		
		$Pankuzu = $actionController->view->getHelper('Pankuzu');
		
		if(is_null($Pankuzu)){
			require_once 'Budori/Exception.php';
			throw new Budori_Exception("cannot find View Helper 'Pankuzu'");
		}
		
		$Pankuzu->setSeparator(' &gt; ')->set('top','/');
	}
	
}
