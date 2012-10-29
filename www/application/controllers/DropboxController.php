<?php
/**
 * DropBox ログインと、APIの呼び出し
 * @author shigeru.ashikawa
 */
class DropboxController extends Neri_Controller_Action_Http 
{	
	/**
	 * @var \Dropbox\API
	 */
	protected $_dropbox = null;
	
	/**
	 * @var \Dropbox\OAuth\Storage\Session
	 */
	protected $_sessionStorage = null;
	
	public function init()
	{
		parent::init();
		Zend_Session::start();
	}
	
	public function preDispatch()
	{
		parent::preDispatch();
		$this->_initDropbox();
	}
	
	private function _initDropbox()
	{
		$key		= 'eflihw92cmmqm0u';
		$secret 	= 't75hdoi0w4pdq13';
		
		require_once 'Dropbox/OAuth/Storage/Session.php';
		$storage	= new \Dropbox\OAuth\Storage\Session();
		
		$this->_sessionStorage = $storage;
		
		$callback	= 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		require_once 'Dropbox/OAuth/Consumer/Curl.php';
		$OAuth = new \Dropbox\OAuth\Consumer\Curl($key, $secret, $storage, $callback);
		
		require_once 'Dropbox/API.php';
		$dropbox = new \Dropbox\API($OAuth);
		
		$this->_dropbox = $dropbox;
	}
	
	/**
	 * ファイルの一覧
	 */
	public function indexAction()
	{
		$dropbox = $this->_dropbox;
		
		try{
			$result = $dropbox->metaData();
		}catch (Exception $e){
			$this->_sessionStorage->delete();
			throw $e;
		}
		$this->view->assign('metadata', $result);
	}
	
	
	public function downloadAction()
	{
		$this->disableLayout();	
		$this->setNoRender();
		
		
		$params = array_merge(array(
			'f'	=> null
		), $this->_getAllParams());
		
		$dropbox = $this->_dropbox;
		
		$filename = $params['f'];
		
		$tmpname = "/tmp/".  md5($filename);
		
		// 一回 tmp　に保存
		$result	= $dropbox->getFile($filename, $tmpname);
		
		
		$filebody	= file_get_contents($tmpname);
		$pathInfo	= pathinfo($filename);
		
		$this->getResponse()
			->setHeader('Content-Disposition', 'attachment; filename="' . $pathInfo['basename'] . '"')
			->setHeader('Content-Type', $result['mime'])
			->setHeader('Content-Length', strlen($filebody))
			->setBody($filebody);
	}
}
