<?php
/**
 * 携帯用、リダイレクター
 */
class  Budori_Mobile_Controller_Action_Helper_Redirector extends Zend_Controller_Action_Helper_Abstract 
{
	public function direct($url, $options = array())
	{
		if( empty($_COOKIE) && Zend_Session::getOptions('use_only_cookies') != '1' ){
			
			
			if ( $id = Zend_Session::getId() ) {
				
				$name	= session_name();
				$id		= strip_tags($id);
				
				$query = "";
				list($url, $query) = explode('?', $url, 2);
				
				$url .= sprintf('?%s=%s', $name, urlencode($id));
				
				if ($query) {
					$url .= sprintf('&%s', $query);
				}
			}
		}
		$this->_redirect($url, $options);
	}
	
	protected function _redirect($url, $options = array())
	{
		Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')->gotoUrl($url, $options);
	}
}
