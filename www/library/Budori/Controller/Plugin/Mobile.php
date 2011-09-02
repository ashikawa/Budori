<?php
/**
 * @todo 作業中
 * docomo 端末のリンクにguid=ON を自動付加
 * 
 */
class Budori_Controller_Plugin_Mobile extends Zend_Controller_Plugin_Abstract 
{
	/**
	 * @var Budori_Mobile
	 */
	protected $_mobile;
	
	
	
	public function __construct()
	{
		$this->_mobile = new Budori_Mobile();
	}
	
	
	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{
	    parent::routeStartup($request);
		
	    ini_set('default_charset','SJIS');
	    $this->getResponse()->setHeader('Content-Type','application/xhtml+xml')
					->setHeader('charset','SJIS');
		
		
		if( $this->_mobile->searchCarrier() == Budori_Mobile::CARRIER_DOCOMO ){
			
			parse_str(str_replace(',','&',ini_get("url_rewriter.tags")), $tags);
			
			$tags['form'] = "action";
			
			ini_set("url_rewriter.tags", http_build_query($tags,null,','));
			
			output_add_rewrite_var('guid', 'ON');
		}
	}
	
	/**
	 * Filter と Layout を同時に使うと文字化けするため。
	 * @todo 何か上手い方法が無いか考える。
	 */
	public function dispatchLoopShutdown()
	{
		parent::dispatchLoopShutdown();
		
		$response = $this->getResponse();
		
		$body = $response->getBody(true);
		
		if( is_string($body) ){
			
			$bodyConverted = $this->_convertEncoding($body);
			$response->setBody($bodyConverted);
			
		}else if(is_array($body)){
			
			$bodyConverted = array_map(array($this,'_convertEncoding'), $body);
			
			foreach ($bodyConverted as $_name => $_content){
				$response->setBody($_content,$_name);
			}
			
		}else{
			
//			throw new Zend_Exception();
		}
	}
	
	protected function _convertEncoding($body)
	{
		return mb_convert_encoding($body, 'SJIS','auto');
	}
}
