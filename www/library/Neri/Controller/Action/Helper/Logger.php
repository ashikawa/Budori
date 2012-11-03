<?php
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * ログ出力ヘルパー
 * ヘルパーは、アクションコントローラの各メソッドの前に実行される
 *
 * preDispatch で名前を保存しているのは _foward() 等を実行すると
 * request オブジェクトの値が変わって、postDispatch() に foward 先の
 * 名前が出力されるのを防ぐため。
 */
class Neri_Controller_Action_Helper_Logger extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Enter description here...
     * @var string
     */
    protected $_moduleName;

    /**
     * Enter description here...
     * @var string
     */
    protected $_controllerName;

    /**
     * Enter description here...
     * @var string
     */
    protected $_actionName;

    public function direct($message, $priority = Zend_Log::INFO)
    {
        $this->_getLogger()->log($message,$priority);
    }

    public function init()
    {
        parent::init();
        $request	= $this->getRequest();

        $module		= $request->getModuleName();
        $controller	= $request->getControllerName();
        $action		= $request->getActionName();

        $this->direct("[$controller/$action] " . __FUNCTION__, Zend_Log::INFO);
    }

    public function preDispatch()
    {
        parent::preDispatch();
        $request	= $this->getRequest();

        $module		= $request->getModuleName();
        $controller	= $request->getControllerName();
        $action		= $request->getActionName();

        $this->_moduleName		= $module;
        $this->_controllerName	= $controller;
        $this->_actionName		= $action;

        $this->direct("[$controller/$action] " . __FUNCTION__, Zend_Log::INFO);
    }

    public function postDispatch()
    {
        parent::postDispatch();

        $module		= $this->_moduleName;
        $controller	= $this->_controllerName;
        $action		= $this->_actionName;

        $this->direct("[$controller/$action] " . __FUNCTION__, Zend_Log::INFO);
    }

    /**
     * Enter description here...
     * @return Budori_Log
     */
    protected function _getLogger()
    {
        require_once 'Budori/Log.php';

        return Budori_Log::factory();
    }
}
