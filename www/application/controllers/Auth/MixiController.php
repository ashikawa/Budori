<?php
/**
 * OpenID (mixi) 用　ログインコントローラ 
 * ログアウトは共通で良い
 * Mixi限定なので、認証用のクラスは分けなくても良い、としておく。
 * 
 * <ul>何故かテストサーバー(.118)でエラー発生。 session周りっぽい。だがローカルでは動いている。
 * php.ini の　extension=php_curl.dll があるとエラーが発生。(Auth_Yadis_Yadis::curlPresent()を見ろ)
 * ローカル、Linux　両方から削除するか、両方に読み込ませて対応するか検討中。</ul>
 * 
 * @todo define('Auth_Yadis_CURL_OVERRIDE',true); のところが若干胡散臭いです。
 * ライブラリのバージョンアップとかで使えなくなる可能性あり。
 */
class Auth_MixiController extends Neri_Controller_Action_Http 
{
	
	const LIB_OPEN_ID	= "/library/php-openid-2.1.3";
	
	const OPEN_ID_URL	= 'https://mixi.jp';
	
	const STORE_DIR		= CACHE_DIR;
	
	public function init()
	{
		parent::init();
		
		set_include_path('.'
		 . PATH_SEPARATOR . realpath( ROOT . self::LIB_OPEN_ID )
		 . PATH_SEPARATOR . get_include_path()
		);
		
		define('Auth_Yadis_CURL_OVERRIDE',true);
		
		Zend_Session::start();
	}
	
	public function indexAction()
	{
		$this->setNoRender();
		$this->disableLayout();
		
		$openid		= self::OPEN_ID_URL;
		$consumer	= $this->getConsumer();
		
		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin($openid);
		
		// No auth request means we can't begin OpenID.
		if (is_null($auth_request)) {
			throw new Budori_Exception("Authentication error; not a valid OpenID.");
		}
		
		require_once 'Auth/OpenID/SReg.php';
		
		$sreg_request = Auth_OpenID_SRegRequest::build(array('nickname'));
		
	    if ($sreg_request) {
	        $auth_request->addExtension($sreg_request);
	    }
		
	    $policy_uris = null;
		
	    require_once 'Auth/OpenID/PAPE.php';
	    
	    $pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
	    if ($pape_request) {
	        $auth_request->addExtension($pape_request);
	    }
		
        $redirect_url = $auth_request->redirectURL($this->getTrustRoot(),$this->getReturnTo());
		
        // If the redirect URL can't be built, display an error message.
        if (Auth_OpenID::isFailure($redirect_url)) {
            throw new Budori_Exception("Could not redirect to server: " . $redirect_url->message);
        } else {
            // Send redirect.
            return $this->_redirect($redirect_url);
        }
	}
	
	
	
	public function confAction()
	{
	    $consumer = $this->getConsumer();
		
	    // Complete the authentication process using the server's
	    // response.
	    $return_to	= $this->getReturnTo();
	    $response	= $consumer->complete($return_to);
	
	    // Check the response status.
	    if ($response->status == Auth_OpenID_CANCEL) {
	    	
	    	throw new Budori_Exception('Verification cancelled.');
	    	
	    } else if ($response->status == Auth_OpenID_FAILURE) {
	    	
	    	throw new Budori_Exception("OpenID authentication failed: " . $response->message);
	    	
	    } else if ($response->status == Auth_OpenID_SUCCESS) {
	    	
	        $openid = $response->getDisplayIdentifier();
			$this->view->assign('openId',$openid);
	        
			require_once 'Auth/OpenID/SReg.php';
	        
	        $sreg_resp	= Auth_OpenID_SRegResponse::fromSuccessResponse($response);
	        $content	= $sreg_resp->contents();
	        
	        $db = Budori_Db::factory();
	        
			$member = Neri_Member::getInstance();
			
			//ログイン済みなら自動設定とか
			//if( $member->isLogin() ) { ...... }
			
			$auth = new Neri_Auth_Adapter_Mixi($db);
			$auth->setMixiId( $openid );
			
			$result = $member->login( $auth );
			
			
			if( !$result->isValid() ){
				/**
				 * @todo 認証失敗時の処理 or 会員登録?
				 */
				var_export($result->getMessages());exit;
			}
	        
			$this->view->assign('content',$content);
	    }
	    
	}
	
	/**
	 * Enter description here...
	 * @return Auth_OpenID_FileStore
	 */
	public function getStore()
	{
		$store_path = self::STORE_DIR;
		
	    if (!file_exists($store_path) && !mkdir($store_path)) {
	        throw new Budori_Exception("Could not create the FileStore directory '$store_path'.  Please check the effective permissions.");
	    }
	    require_once 'Auth/OpenID/FileStore.php';
	    return new Auth_OpenID_FileStore($store_path);
	}
	
	
    /**
     * Create a consumer object using the store object created
     * earlier.
     * @return Auth_OpenID_Consumer
     */
	public function getConsumer()
	{
		require_once 'Auth/OpenID/Consumer.php';
	    return new Auth_OpenID_Consumer($this->getStore());
	}
	
	public function getReturnTo()
	{
		$request = $this->getRequest();
	    return sprintf("%s://%s/auth_mixi/conf", $request->getScheme(), $request->getHttpHost());
	}
	
	public function getTrustRoot()
	{
		$request = $this->getRequest();
		return sprintf("%s://%s%s/", $request->getScheme(), $request->getHttpHost(), '/auth_mixi');
	}
	
}
