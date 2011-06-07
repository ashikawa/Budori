<?php

class MypageController extends Neri_Controller_Action_Http 
{
	
	/**
	 * Enter description here...
	 * @var Neri_Member
	 */
	protected $_member;
	
	public function init()
	{
		parent::init();
		$this->_member = Neri_Member::getInstance();
	}
	
	public function preDispatch()
	{
		parent::preDispatch();
		
		$this->_loginCheck();
		
		$this->appendPankuzu('マイページ','/' . $this->getRequest()->getControllerName() );
		$this->prependTitle('マイページ');
	}
	
	
	/**
	 * mixiにコメントの投稿
	 */
	public function mixiAction()
	{
		$this->prependTitle('mixi');
		$this->appendPankuzu('mixi');
		
		$params = $this->_getAllParams();
		
		/**
		 * @todo validation ErrorHandler
		 */
		if( isset($params['title']) && isset($params['comment']) ){
			
			$member = $this->_member;
			
			$user	= $member->mixi_user;
			$pass	= $member->mixi_pass;
			$id		= $member->mixi_id;
			
			$mixi = new Budori_Service_Mixi($user,$pass,$id);
			$mixi->send( $params['title'], $params['comment'] );
		}
	}
	
}
