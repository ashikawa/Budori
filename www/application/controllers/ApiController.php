<?php

/**
 * Ajax通信用
 * @see http://framework.zend.com/manual/ja/zend.controller.actionhelpers.html
 * jsonは便利だがxmlの出力は無理にcontextSwitchを使う必要は無さそう。
 */
class ApiController extends Budori_Controller_Action 
{
	const ZIP_LIMIT = 10;
	
	const MAP_LIMIT = 100;
	
	
	public $contexts = array(
		'sample'	=> array('json'),
		'zip'		=> array('json'),
		'map'		=> array('json'),
		'valid'		=> array('json'),
	);
	
	/**
	 * 通常リクエストは404
	 */
	public function init()
	{
		parent::init();
		
//		if( !$this->getRequest()->isXmlHttpRequest() ){
//			throw new Zend_Controller_Action_Exception('wrong request', 404);
//		}
//		header("Content-Type: text/javascript; charset=utf-8"); 
		$contextSwitch = $this->_helper->getHelper('contextSwitch')
											->initContext('json');
		
		
		//Smarty用。　Zend_View でも問題ない
		$this->view->clearVars();
	}
	
	/**
	 * 配列をJsonで返す
	 */
	public function sampleAction()
	{
		
		$key = $this->_getParam('key',null);
		
		if( is_null($key) ){
			$rtn = array(
				's1'	=> 's1',
				's2'	=> 's2',
			);
			
		}else {
			
			$data = array(
				's1'	=> array('m1'=>'m1','m2'=>'m2','m3'=>'m3'),
				's2'	=> array('m4'=>'m4','m5'=>'m5'),
				'm1'	=> array('l1' => 'l1', 'l2' => 'l2'),
				'm2'	=> array('l3' => 'l3', 'l4' => 'l4'),
				'm3'	=> array('l5' => 'l5', 'l6' => 'l6'),
				'm4'	=> array('l7' => 'l7', 'l8' => 'l8'),
				'm5'	=> array('l9' => 'l9', 'l10' => 'l10'),
			);
			if(isset($data[$key])){
				
				$rtn = $data[$key];
			}else {
				
				$rtn = null;
			}
		}
		
		$this->show($rtn);
	}
	
	
	/**
	 * zip search
	 */
	public function zipAction()
	{
		
		$code = $this->_getParam('code',null);
		
		$rtn = array();
		
		if( !is_null($code) ){
			$db = Budori_Db::factory();
			
			$select = new Neri_Db_Select_Zip($db);
			$select->codeLike($code)->limit( self::ZIP_LIMIT );
			
			$rec = $db->fetchAll($select);
			
			foreach ($rec as $val){
				$rtn[$val->code] = "{$val->pref} {$val->city} {$val->town}";
			}
		}
		$this->show($rtn);
	}
	
	/**
	 * google map
	 */
	public function mapAction()
	{
		$db = Budori_Db::factory();
		
		$select = new Neri_Db_Select_Event($db);
		$select->limit(self::MAP_LIMIT);
		
		$list = $db->fetchAll($select);
		
		$this->show($list);
	}
	
	
	
	
	
	public function validIdAction()
	{
		$params = array(
				'val'		=> $this->_getParam('val'),
				'rule'		=> "Zend_Validate_Db_NoRecordExists",
				'args'		=> array(
									'table'	=> 'member',
									'field'	=> 'key',
								),
			);
		
		$this->_forward('valid',null,null,$params);
	}
	
	/**
	 * @example /api/valid?val=ashikawa&rule=Db_NoRecordExists&_t=member&_c=key
	 */
	public function validAction()
	{
		$params = $this->_getAllParams();
		
		
		$rule	= null;
		$val	= null;
		$args	= array();
		
		foreach ($params as $key => $value){
			switch ($key){
				case 'rule':
				case 'val':
					if($this->getRequest()->isGet()){
						$value = urldecode($value);
					}
					$$key = $value;
					break;
				case 'args':
					$$key = $value;
					break;
				default:
					break;
			}
		}
		$result = array(
			'status'	=> Zend_Validate::is($val,$rule,$args),
		);
		
		$this->show($result);
	}
	
	/**
	 * 出力
	 * @param array $result
	 */
	protected function show( $result )
	{
		$this->view->assign($result);
	}
}
