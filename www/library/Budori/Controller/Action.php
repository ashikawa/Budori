<?php
require_once 'Zend/Controller/Action.php';


/**
 * アクションコントローラ
 * 主にヘルパーへのショートカットメソッドの提供
 */
class Budori_Controller_Action extends Zend_Controller_Action  
{	
	
	/**
	 * コントローラディレクトリを使用しない
	 * @param boolean $flag
	 */
	public function setNoController( $flag = true )
	{
		$this->getHelper('viewRenderer')->setNoController($flag);
	}
	
	/**
	 * 保管用
	 * @return Zend_Application_Bootstrap_Bootstrap | null
	 */
	public function getBootstrap()
	{
		return $this->getFrontController()->getParam('bootstrap');
	}
	
	/**
	 * ViewRenderの解除
	 * @param boolean $flag
	 */
	public function setNoRender( $flag = true )
	{
		$this->getHelper('viewRenderer')->setNoRender($flag);
	}
	
	/**
	 * レイアウトの変更
	 * @param string $src
	 */
	public function setLayout( $src )
	{
		$this->getHelper('Layout')->setLayout( $src );
	}
	
	/**
	 * レイアウトを使用しない
	 */
	public function disableLayout()
	{	
		$this->getHelper('Layout')->disableLayout();
	}
}
