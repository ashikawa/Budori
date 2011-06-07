<?php
/**
 * @todo エラーメッセージへに変数を埋め込む処理
 * @todo Form周りの処理は、落ち着いたら抽象クラス化? するかも。
 */
class Account_EditController extends Neri_Controller_Action_Http 
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
		
		
		$this->appendPankuzu('アカウント編集','/' . $this->getRequest()->getControllerName() );
		$this->prependTitle('アカウント編集');
	}
	
	public function indexAction()
	{
		$member = $this->_member->toArray();
		$member['pass1'] = $member['pass'];
		$member['pass2'] = $member['pass'];
		
		$params = array_merge(
			$member, 
			$this->_getAllParams() );
		
		
		$this->view->assign($params);
	}
	
	public function confAction()
	{
		$this->appendPankuzu('確認');
		
		$params = $this->_getAllParams();
		
		$form = $this->getForm($params);
		
		if( !$form->isValid() ){
			
			$this->view->assign( 'messages', $form->getMessages() );
			return $this->_forward( 'index' );
		}
		
		
		$transaction = new Budori_TransactionToken();
		$transaction->saveToken();
		
		$assigns = array(
			'filtered'		=> $form->getUnescaped(),
			'transaction'	=> $transaction,
		);
		
		$this->view->assign($assigns);
	}
	
	
	/**
	 * @todo ここの例外処理を意識しないようにしたいが、クラスの中に例外処理を書くのも違う気がする。
	 * というか、面倒な事になるからDBのトランザクション制御はコントローラー以外でやりたくない。
	 * トランザクションを二回張ると、
	 * PDOException[There is already an active transaction]
	 * とエラーが出るので、一つのアクションで一つのトランザクションが完結するように書きたいです。
	 */
	public function endAction()
	{
		$this->appendPankuzu('完了');
		
		if( !Budori_TransactionToken::is() ){
			return ;
		}
		
		$member = $this->_member;
		
		$params = $this->_getAllParams();
		
		$member->name		= $params['name'];
		$member->mixi_id	= $params['mixi_id'];
		$member->pass		= $params['pass1'];
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$db->beginTransaction();
		
		try {
			
			$member->save();
			
		//　catch ( Zend_Db_Exception ...) とかは書かない。　例外は全てロールバックする。
		} catch ( Exception $e ){
			
			$db->rollBack();
			throw $e;
		}
		$db->commit();
	}
	
	/**
	 * Enter description here...
	 * @param array $params
	 * @return Budori_Filter_Input
	 */
	protected function getForm( $params, $options = array() )
	{
		return new Budori_Filter_Input( $this->_getFilter(), $this->_getValidator(), $params, $options );
	}
	
	/**
	 * Enter description here...
	 * @return array
	 */
	protected function _getFilter()
	{
		return $this->_getConfig('filter');
	}
	
	/**
	 * Enter description here...
	 * @return array
	 */
	protected function _getValidator()
	{
		return $this->_getConfig('valitetor');
	}
	
	/**
	 * Enter description here...
	 * @return Zend_Config
	 */
	protected function _getConfig($section)
	{
		$controller = strtolower($this->getRequest()->getControllerName());
		return Budori_Config::factory("validate/$controller.ini", $section)->toArray();
	}
}
