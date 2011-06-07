<?php
require_once 'Zend/Controller/Front.php';

/**
 * トランザクショントークン
 * ディフォルトの識別子として、コントローラー名が使われる。
 * 同じコントローラ内に複数のトランザクションを含む場合は、コンストラクタの引数が必要。
 */
class Budori_TransactionToken
{
	/**
	 * transaction name
	 * @var string
	 */
	protected $_transactionName;
	
	/**
	 * session object
	 * @var Zend_Session_Namespace
	 */
	protected $_session = null;
	
	/**
	 * POST or GET paramater's name
	 * @var string
	 */
	protected $_postName = "_token";
	
	/**
	 * saved token
	 * @var string
	 */
	protected $_token;
	
	/**
	 * default transactionName is ControllerName
	 * @param string $transactionName
	 */
	public function __construct( $transactionName = null )
	{
		if( is_null($transactionName) ){

			$request = Zend_Controller_Front::getInstance()
							->getRequest();
			
			$module		= $request->getModuleName();
			$controlelr	= $request->getControllerName();
			
			$transactionName = $module . "_" . $controlelr;
		}
		
		$this->_noCache();
		
		$this->_transactionName = $transactionName;
	}
	
	/**
	 * get saved token or null
	 * @return string | null
	 */
	public function getToken()
	{
		return $this->_token;
	}
	
	/**
	 * get post paramater name
	 * @return string
	 */
	public function getPostName()
	{
		return $this->_postName;
	}
	
	/**
	 * トランザクショントークンの保存
	 */
	public function saveToken()
	{
		$token = $this->_generateToken();
		
		$this->_token = $token;
		
		$session = $this->_getSession();
		$session->{$this->_transactionName} = $token;
		$session->lock();
		
		return $token;
	}
	
	/**
	 * is token saved
	 * @return boolean
	 */
	public function isSaved()
	{
		return !is_null($this->_token);
	}
	
	/**
	 * トランザクショントークンの確認
	 * @return boolean
	 */
	public function isTokenValid( $token = null, $reset = true )
	{
		$session = $this->_getSession();
		$sToken = $session->{$this->_transactionName};
		
		if(is_null($token)){
			
			$param = array_merge($_GET,$_POST);
			if(array_key_exists($this->_postName,$param)){
				$token = $param[$this->_postName];
			}
		}
		
		$this->_token = $token;
		
		if($reset){
			$this->resetToken();
		}
		
		return !is_null($sToken) && ( $sToken == $token);
	}
	
	/**
	 * Enter description here...
	 * @param string $token
	 * @param boolean $reset
	 * @param string $tokenName
	 * @return boolean
	 */
	public static function is( $token = null, $reset = true, $tokenName= null  )
	{
		$instance = new self( $tokenName );
		return $instance->isTokenValid($token,$reset);
	}
	
	/**
	 * reset token
	 */
	public function resetToken()
	{
		$this->_token = null;
		
		$session = $this->_getSession();
		$session->{$this->_transactionName} = null;
	}
	
	/**
	 * トークンの生成
	 * @return string
	 */
	protected function _generateToken()
	{
		return md5(mt_srand());
	}
	
	/**
	 * セッションオブジェクトの生成と取得
	 * @return Zend_Session_Namespace
	 */
	protected function _getSession()
	{
		if(is_null($this->_session)){
			require_once 'Zend/Session/Namespace.php';
			$this->_session = new Zend_Session_Namespace(get_class($this));
		}
		return $this->_session;
	}
	
	/**
	 * 画面をブラウザにキャッシュさせない
	 */
	protected function _noCache()
	{
		$responce = Zend_Controller_Front::getInstance()
						->getResponse();
		
		$responce->setHeader('Cache-Control','private')
					->setHeader('Pragma','no-cache');
	}
}
