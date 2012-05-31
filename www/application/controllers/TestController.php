<?php

/**
 * 各種動作確認
 * 簡略化してあるため、実際に使用する場合は、入力チェック、例外処理を確認してから。
 */
class TestController extends Neri_Controller_Action_Http
{
	const TEST_STYLE_SHEET_PATH = '/style/content/test.css';

	public function init()
	{
		//View_Helper_HeadLink は同じ内容のものは上書きされるので、_foward() 時の初期化不要
		$this->appendHeadLink(self::TEST_STYLE_SHEET_PATH);
	}

	public function preDispatch()
	{
		parent::preDispatch();

		$this->prependTitle('動作確認');
		$this->appendPankuzu('動作確認','/' . $this->getRequest()->getControllerName() );
	}

	public function indexAction()
	{}

	/**
	 * Zend_Service_Amazon
	 */
	public function amazonAction()
	{
		$this->prependTitle('amazon');
		$this->appendPankuzu('amazon');


		$params = array_merge( array(
				'query'	=> null,
			),$this->_getAllParams());


		$record = array();

		if(!is_null($params['query']) && !empty($params['query']) ){

			$model = new Amazon();
			$record = $model->searchClassic($params['query']);
		}

		$this->view->assign('record',$record);
	}

	/**
	 * Zend_Gdata_YouTube
	 */
	public function youtubeAction()
	{
		$request = $this->getRequest();

		$this->prependTitle('youtube');
		$this->appendPankuzu('youtube');



		$params = array_merge( array(
				'query'		=> null,
				'offset'	=> 0,
				'results'	=> 5,
			),$this->_getAllParams());

		$result = array();

		if( !is_null($params['query']) && !empty($params['query']) ){

			$model = new Youtube();

			$result = $model->setMaxResults( (int)$params['results'] )
							->setStartIndex( (int)$params['offset'] )
							->serach($params['query']);
		}

		$this->view->assign('result',$result);
	}

	public function qrcodeAction()
	{
		$this->prependTitle('qrCode');
		$this->appendPankuzu('qrCode');

	}

	public function googleAction()
	{
		$this->prependTitle('google news');
		$this->appendPankuzu('google news');
	}

	public function feedAction()
	{
		$this->prependTitle('feed');
		$this->appendPankuzu('feed');

		$url = 'http://www.shinchosha.co.jp/feed/bunko.xml';
		$feed = Zend_Feed::import($url);

		$this->view->assign('feed',$feed);
	}

	public function mapAction()
	{
		$this->setLayout('simple');
			
		$this->prependTitle('map');
		$this->appendPankuzu('map');
		
		$this->view->assign(array(
			'googleApiKey' => 'ABQIAAAAAX8aCkb-oZ6qr72p4b_6dBSDtkJ9YB9wA7aTuJwdFI_zFWr-8BThLhSEuQmX2GuO6SQvxv695B3uYA',
		));
	}

	/**
	 * ログ出力の例
	 */
	public function logAction()
	{
		$this->prependTitle('log');
		$this->appendPankuzu('log');

		$log = Budori_Log::factory();
		
//		const EMERG   = 0;  // Emergency: system is unusable
//		const ALERT   = 1;  // Alert: action must be taken immediately
//		const CRIT    = 2;  // Critical: critical conditions
//		const ERR     = 3;  // Error: error conditions
//		const WARN    = 4;  // Warning: warning conditions
//		const NOTICE  = 5;  // Notice: normal but significant condition
//		const INFO    = 6;  // Informational: informational messages
//		const DEBUG   = 7;  // Debug: debug messages
		
		$log->emerg(__CLASS__."(".__LINE__.") EMERG");
		$log->alert(__CLASS__."(".__LINE__.") ALERT");
		$log->crit(__CLASS__."(".__LINE__.") CRIT");
		$log->err(__CLASS__."(".__LINE__.") ERR");
		$log->warn(__CLASS__."(".__LINE__.") WARN");
		$log->notice(__CLASS__."(".__LINE__.") NOTICE");
		$log->info(__CLASS__."(".__LINE__.") INFO");
		$log->debug(__CLASS__."(".__LINE__.") DEBUG");		
	}

	/**
	 * Smartyプラグイン
	 */
	public function smartyAction()
	{
		$this->prependTitle('smarty');
		$this->appendPankuzu('smarty');

		$assign = array(
			'url'		=> 'http://google.com',
			'mail'		=> 'ashikawa@snappy.ne.jp',
			'date'		=> mktime(),
			'record'	=> array(
							'hoge0','hoge1','hoge2',
							'hoge3','hoge4','hoge5',
							'hoge6','hoge7','hoge8',
							'hoge9',
						),
			'example'	=> array('a' => 'apple', 'b' => 'banana'),
			'class'		=> new Zend_Auth_Result(1,1),
		);

		$this->view->assign($assign);
	}


	/**
	 *　foward の戻り先を指定するサンプル
	 * 「お気に入りに追加」等の場合
	 */
	public function fowardAction()
	{
		$this->prependTitle('foward');
		$this->appendPankuzu('foward');

		var_export($this->_getAllParams());
	}

	/**
	 * ロジックだけ記述したコントローラ
	 * 戻り先が無ければ not found (若しくはディフォルトの戻り先を指定しておく)
	 */
	public function foward2Action()
	{
		$params = array_merge( array(
			'_controller'	=> null,
			'_action'		=> null,
			'_module'		=> null,
			'id'			=> null,
		), $this->_getAllParams() );

		//ビジネスロジック

		if( is_null($params['_action']) ){
			throw new Zend_Controller_Action_Exception('not found "_action"',404);
		}
		$this->_forward( $params['_action'], $params['_controller'], $params['_module'], $params );
	}


	/**
	 * 翻訳アダプタ　動作確認。
	 * おそらくViewScript配下のディレクトリを分割切り替えした方が良い。
	 * Validateのメッセージ等、動的な部分に組み込みのかな？
	 */
	public function translateAction()
	{
		$this->prependTitle('translate');
		$this->appendPankuzu('translate');

		$ja = array(
			'welcome' => 'ようこそ',
			'time' => '今の時刻は %1$s です。'
		);
		$en = array(
			'welcome' => 'welcome',
			'time' => 'It is %1$s now.'
		);

		//Zend_Translateオブジェクト生成（翻訳アダプタの生成）
		$translate = new Zend_Translate('array', $ja, 'ja');
		$translate->addTranslation($en, 'en')
					->setLocale('auto');

		$locale = new Zend_Locale();
		var_export( explode('_', $locale->findLocale()) );

		echo $translate->_('welcome');
	}

	/**
	 * 自動エスケープ処理の挙動確認
	 */
	public function escapeAction()
	{
		$this->prependTitle('escape');
		$this->appendPankuzu('escape');

		$assign = array(
			'hoge'	=> '<b>hoge</b>',
			'moge'	=> array('<i>mo</i>','<i>ge</i>'),
			'hage'	=> new ArrayObject( array('<i>ha</i>','<i>ge</i>') ),
		);

		$this->view->assign($assign);
	}


	/**
	 * HTMLメールの送信テスト
	 */
	public function mailAction()
	{
		$this->prependTitle('mail');
		$this->appendPankuzu('mail');

		$body		= $this->_getParam('body',null);

		if(!is_null($body)){

			$db		= Budori_Db::factory();
			$config	= Budori_Config::factory('mail.ini','test');
			
			$mail = new Mail_Test($config);
			$mail->setAddressList(new Mail_AddressList_Dummy());
//			$mail->setAddressList(new Mail_AddressList_Member($db));
			$mail->getView()->assign('body',$body);
			$mail->send();
		}
	}
	
	/**
	 * 例外の取り扱いサンプル
	 * 基本的には、例外は処理した後にもう一度投げる。
	 * 処理が必要なければ、ただ無視する。(catchしない)
	 */
	public function exceptionAction()
	{
		try {
			$config = Budori_Config::factory('hogehoge.ini');
		}catch (Exception $e){

			// any process ex) $db->rollback() ...

			//例外を変換したい時。
			throw new Budori_Exception($e->getMessage(),0,$e);

			//普通は、例外処理した後は処理をエラーハンドラまで投げる。
			//throw $e;
		}
	}


	/**
	 * Cookie操作。　これはボツ。
	 * Zend_Http_Cookie は、Zend_Http_Client とかで別サーバーにリクエスト投げるための物だと思われる。
	 */
	public function cookieAction()
	{
		$this->prependTitle('cookie');
		$this->appendPankuzu('cookie');

		$name		= 'example';					//cookie name(ex 'PHPSESSID')

		$value = $this->getRequest()
						->getCookie($name);
		
		//init or cout up
		if(is_null($value)){
			$value = 0;
		}else{
			$value = (int)$value + 1;
		}


		$domain		= $this->getRequest()
						->getServer('SERVER_NAME');	//cookie domain
		$expires	= time() + 7200;				//expires for cookie
		$path	 	= '/';							//cookie path

		$cookie = new Zend_Http_Cookie($name, $value, $domain, $expires, $path);
		$this->getResponse()
				->setHeader('Set-Cookie',$cookie);

		var_export($cookie->__toString());
		$this->view->assign('value',$value);
	}


	/**
	 * (おそらく)最適な Cookie 操作サンプル。
	 * canSendHeaders() が渋いと思われる。
	 */
	public function cookie2Action()
	{
		$this->prependTitle('cookie2');
		$this->appendPankuzu('cookie2');


		$name		= 'example';					//cookie name(ex 'PHPSESSID')

		$value = $this->getRequest()
						->getCookie($name);

		//init or cout up
		if(is_null($value)){
			$value = 0;
		}else{
			$value = (int)$value + 1;
		}

		if( $this->getResponse()->canSendHeaders() ){
			
			$expires	= time() + 7200;				//expires for cookie
			$path	 	= '/';							//cookie path

			setcookie($name,$value,$expires,$path);
		}

		$this->view->assign('value',$value);
	}

	/**
	 * 拡張子を変更した場合のリクエストヘッダの確認
	 * router.ini test_request　を参照。
	 */
	public function requesthederAction()
	{
		$this->prependTitle('request heder');
		$this->appendPankuzu('request heder');

		$assigns = array(
			'accept'	=> $this->getRequest()->getHeader('Accept') ,
			'ext'		=> $this->_getParam('ext'),
			'headers'	=> getallheaders(),
		);

		$this->view->assign($assigns);
	}
	
	
	/**
	 * mecab
	 */
	public function mecabAction()
	{
		$this->prependTitle('mecab');
		$this->appendPankuzu('mecab');
		
		
		$string = $this->_getParam('string');
		
		if(!empty($string)){
			
			if(!class_exists('MeCab_Tagger')){
				throw new Budori_Exception('please install mecab-php');
			}
			
			$mecab = new MeCab_Tagger(); 
			$node = $mecab->parseToNode($string);
			
			$this->view->assign('node',$node);
		}
	}
	
	public function canvas3dAction()
	{}
	
	public function webglAction()
	{
		$this->setLayout("simple");
	}
}
