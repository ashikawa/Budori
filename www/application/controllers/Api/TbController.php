<?php

/**
 * Thickboxインラインメニュー用コントローラ
 *　Api でも Inc でも微妙なので独立
 */
class Api_TbController extends Budori_Controller_Action 
{
	public function init()
	{
		$this->disableLayout();
		
		if( !$this->getRequest()->isXmlHttpRequest() ){
			throw new Zend_Controller_Action_Exception('wrong request', 404);
		}
	}
	
	public function submenuAction()
	{
		$params = array_merge(
			array('p' => '1'),
			$this->_getAllParams()
		);
		
		$this->view->assign( $params );
	}
	
}
