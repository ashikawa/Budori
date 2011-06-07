<?php
require_once 'Zend/Application/Resource/ResourceAbstract.php';
require_once 'Zend/Controller/Action/HelperBroker.php';

/**
 * bootstrap resources for Action Contoller Helper
 * ディフォルトのアクションヘルパーを設定する
 */
class Budori_Application_Resource_Controllerhelper extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
		$options = $this->getOptions();
		
		if(isset($options['prefix'])){
			$this->initPrefix($options['prefix']);
		}
		
		if(isset($options['add'])){
			$this->addHelper($options['add']);
		}
		
		return NULL;
    }
    
    public function initPrefix( $preix )
    {
    	if(!is_array($preix)){
    		$preix = array( $preix );
    	}
    	
    	foreach ($preix as $_v){
	    	Zend_Controller_Action_HelperBroker::addPrefix($_v);
    	}
    }
    
    protected function addHelper( $helpers )
    {
    	if(!is_array($helpers)){
    		$helpers = array( $helpers );
    	}
    	
    	foreach ($helpers as $_v){
	    	Zend_Controller_Action_HelperBroker::getStaticHelper($_v);
    	}
    }
}
