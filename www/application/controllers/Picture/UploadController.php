<?php
/**
 * 画像アップロードコントローラ
 * 認証)
 * 	ログイン状態
 */
class Picture_UploadController extends Neri_Controller_Action_Http 
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
	
	/**
	 * 初期化、認証処理
	 */
	public function preDispatch()
	{
		parent::preDispatch();
		
		$this->_loginCheck();
		
		
		$this->prependTitle('picture');
		$this->appendPankuzu('picture','/' . $this->getRequest()->getControllerName() );
	}
	
	
	public function indexAction()
	{
		$this->prependTitle('upload');
		$this->appendPankuzu('upload');
		
		$transaction = new Budori_TransactionToken();
		$transaction->saveToken();
		
		$this->view->assign('transaction',$transaction);
	}
	
	public function confAction()
	{
		if( Budori_TransactionToken::is() ){
			
			$upload = new Budori_Upload('pict1');
			
			$mediaCreate = new Media_Uploader( $upload );
			
			if( !$mediaCreate->isUploadSuccess() ){
				$this->view->assign( 'message', $mediaCreate->getUploadMessage() );
				return $this->_forward('index');
			}
			
			if( !$mediaCreate->isValid() ){
				$this->view->assign( 'message', $mediaCreate->getValidateMessage() );
				return $this->_forward('index');
			}
			
			$db = Budori_Db::factory();
			$db->beginTransaction();
			
			$owner = $this->_member->getIdentity();
			
			try{
				$ret = $mediaCreate->saveFile($db, $owner);
				
			}catch (Exception $e){
				
				$db->rollBack();
				throw $e;
				
			}
			
			$db->commit();
		}
		
		$options = array(
//			'exit' 			=> true,
//			'prependBase'	=> true,
			'code'			=> 303,		//redirect code 'See Other'
		);
		$this->_redirect("/picture", $options);
	}
}
