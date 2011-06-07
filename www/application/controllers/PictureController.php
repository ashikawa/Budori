<?php

/**
 * 画像の一覧
 */
class PictureController extends Neri_Controller_Action_Http 
{
	const PICTURE_STYLESHEET_PATH = '/style/content/picture.css';
	
	
	public function init()
	{
		$this->appendHeadLink(self::PICTURE_STYLESHEET_PATH);
	}
	
	public function preDispatch()
	{
		parent::preDispatch();
		
		$this->prependTitle('picture');
		$this->appendPankuzu('picture','/' . $this->getRequest()->getControllerName() );
	}
	
	public function indexAction()
	{
		$db = Budori_Db::factory();
		
		$select = new Neri_Db_Select_Media($db);
		$select->setDefault();
		
		$rowSet = $db->fetchAll($select);
		
		$this->view->assign( 'rowset', $rowSet );
	}
	
	public function thumbnailsAction()
	{
		$this->prependTitle('thumbnails');
		$this->appendPankuzu('thumbnails');
	}
}