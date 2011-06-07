<?php
require_once 'Zend/Controller/Action/Helper/Abstract.php';
require_once 'Budori/Member/Interface.php';

/**
 * ログイン済みか判定し、ログイン画面へフォワードする。
 * ※ preDispatch() 以外で呼び出すと動作しない or 意図しない動作。
 */
class Neri_Controller_Action_Helper_LoginCheck extends Zend_Controller_Action_Helper_Abstract 
{
	
	public function direct(Budori_Member_Interface $member = null)
	{
		
		if(is_null($member)){
			$member = $this->_getDefaultMember();
		}
		
		if( !$member->isLogin() ){
			$this->_foward();
		}
	}
	
	protected function _foward()
	{
		$request = $this->getRequest();
		
		require_once 'Neri/Const.php';
		$key = Neri_Const::AUTH_REDIRECT_KEY;
		
		$url = $request->getParam($key,null);
		
		if( is_null($url) ){
			$url = $request->getRequestUri();
		}
		
		$request->setParam($key, $url);
		
		$request->setControllerName('auth')
					->setActionName('index')
					->setModuleName('default')
					->setDispatched(false);
	}
	
	protected function _getDefaultMember()
	{
		require_once 'Neri/Member.php';
		return Neri_Member::getInstance();
	}
}
