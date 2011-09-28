<?php
/**
 * 汎用エラーコントローラ
 * 500系のエラーは、$_SERVER['SERVER_ADMIN']　にメール送信
 * この値は httpd.conf の ServerAdmin ディレクティブで指定。
 * 
 * ちなみにErrorコントローラ処理後の例外は Fatal error: Uncaught exception 'Exception'
 * 
 * @todo 本番では、404も報告した方がリンク切れの確認になる。
 */
class ErrorController extends Neri_Controller_Action_Http 
{
	/**
	 * @todo application.ini にでも移す? 検討中
	 * @var bool
	 */
	public $errorReporting = false;
	
	/**
	 * 初期化、ルーティング
	 */
	public function init()
	{
		parent::init();
		$this->setLayout('simple');
		
		$this->getResponse()->clearBody();
		
		$errors		= $this->_getParam('error_handler',null);
		$exception	= null;
		
		if( !is_null($errors) ){
			$exception = $errors->offsetGet('exception');
		}
		
		$this->view->assign(array(
			'exception'		=> $exception,
			'zend_version'	=> Zend_Version::VERSION,
		));
		
		
		if(is_null($errors)){
			return $this->_forward('notfound');
		}
		
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				$this->_forward('notfound');
				break;
				
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
			default:
				$this->_forward('error');
				break;
		}
	}
	
	public function errorAction()
	{
		
		$errors = $this->_getParam('error_handler', null);
		
		if( ($errors->exception instanceof Zend_Controller_Action_Exception)
				&& $errors->exception->getCode() == 400 ){
			
			$this->getResponse()->setHttpResponseCode(400);
			
			$this->appendPankuzu('Bad Request');
			$this->prependTitle('Bad Request');
			
		}else{
			
			$this->getResponse()->setHttpResponseCode(500);
			
			$this->appendPankuzu('エラーが発生しました');
			$this->prependTitle('エラーが発生しました');
		}
		
		$this->_errorReport();
	}
	
	public function notfoundAction()
	{
		$this->getResponse()->setHttpResponseCode(404);
		
		$this->prependTitle('ページが見つかりません');
		$this->appendPankuzu('ページが見つかりません');
		
		// loging section
		$log = Budori_Log::factory();
		
		$ref = $this->getRequest()->getServer('HTTP_REFERER',null);
		if( !is_null($ref)){
			$log->warn("not found page from $ref");
		}
		
		$this->view->assign('url',$this->_maybe());
	}
	
	protected function _maybe()
	{
		if( !isset($_SERVER['REQUEST_URI']) ){
			return null;
		}
		
		// maybe url
		$maybe	= new Maybe_Url();
		$result	= $maybe->search($_SERVER['REQUEST_URI']);
		
		$url = null;
		
		if( $result->getLevenshtein() < 5){
		
			$request = $this->getFrontController()->getRequest();
			$url = $request->getScheme() . "://" . $request->getHttpHost(). $result->getValue();
		}
		
		return $url;
	}
	
	protected function _errorReport()
	{
		if( !$this->errorReporting ){
			return;
		}
		
		$errors		= $this->_getParam('error_handler');
		$exception	= $errors->offsetGet('exception');
		
		$request	= $this->getRequest();
		
		$logger = Budori_Log::factory();
		$logger->err($exception);
		
		if(!is_null($request->getServer('SERVER_ADMIN',null))){
			mail( $request->getServer('SERVER_ADMIN'),
					get_class($exception),
					date(DATE_RSS) . PHP_EOL . $exception);
		}
	}
}
