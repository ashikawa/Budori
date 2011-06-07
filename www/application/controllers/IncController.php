<?php
/**
 * インクルードコンテンツ
 * {zend_action }　での呼び出しが前提
 * _foward禁止
 */
class IncController extends Zend_Controller_Action  
{
	public function dateAction()
	{
		$this->view->assign('today',date('r'));
	}
}
