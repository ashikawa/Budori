<?php

class BlogController extends Neri_Controller_Action_Http 
{
	public function preDispatch()
	{
		parent::preDispatch();
		
		$owner = $this->_getParam('owner', null);
		
		//index 以外
		if(!is_null($owner)){
			$params = $this->_getAllParams();
			$url = $this->getFrontController()->getRouter()
							->assemble($params,'blog_top');
			
			$this->appendPankuzu( $owner, $url );
			
			// ブログモデルとか...
			// $this->_blog = new Blog($param['owner']);
			// .....
		}
	}
	
	public function indexAction()
	{}
	
	public function topAction()
	{}
	
	public function dateAction()
	{}
	
	public function hogeAction()
	{
		$this->appendPankuzu('hoge');
	}
	
}
