<?php
require_once 'Zend/Controller/Plugin/Abstract.php';

class Neri_Controller_Plugin_Profiler extends Zend_Controller_Plugin_Abstract 
{
	
	protected $_viewScript	=  'inc/profiler.phtml';
	
	protected $_layoutName	= 'profile';
	
	
    public function postDispatch(Zend_Controller_Request_Abstract $request)
	{
		parent::postDispatch($request);
		
        if ( $request->isDispatched() ){
        	$this->_output();
        }
    }
    
    protected function _output()
    {
   		require_once 'Zend/Controller/Action/HelperBroker.php';
   		
    	$layout = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');
    	
    	if( $layout->isEnabled() ){
    		
	    	$view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
	    	
	    	$profiles = Budori_Db::factory()->getProfiler()->getQueryProfiles(null,true);
	    	
	    	if(!empty($profiles)){
		    	$view->assign(
		    		'profile', $profiles
		    	);
		    	
		    	
		    	$this->getResponse()->insert( $this->_layoutName, $view->render($this->_viewScript) );
	    	}
    	}
    }
}