<?php
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * アクセス記録等のサンプル
 */
class Neri_Controller_Plugin_Url extends Zend_Controller_Plugin_Abstract
{
    protected $_pageTitle = "";

    /**
     * HeadTitle() は、dispatchLoopShutDown() だと初期化されているため
     * postDispatch() で記録。
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        parent::postDispatch($request);

        require_once 'Zend/Layout.php';
        $layout = Zend_Layout::getMvcInstance();

        $this->_pageTitle = $layout->getView()->HeadTitle()->toString();
    }

    public function dispatchLoopShutDown()
    {
        parent::dispatchLoopShutdown();

        require_once 'Zend/Layout.php';
        $layout = Zend_Layout::getMvcInstance();

        $request = $this->getRequest();

        if (!$request->isDispatched() || $this->getResponse()->isRedirect() ) {
            return;
        }

        if ( $request->isGet() && !$request->isXmlHttpRequest() ) {

            $responce = $this->getResponse();

            if ( $responce->getHttpResponseCode() == 200) {
                $this->_urlEntry();
            }
        }
    }

    protected function _urlEntry()
    {
        $content = null;

        require_once 'Zend/Layout.php';
        $layout = Zend_Layout::getMvcInstance();

        if (!is_null($layout)) {
            $key		= $layout->getContentKey();
            $content	= $layout->$key;

            $content	= preg_replace('/^(\s)+/sm','',preg_replace('/^(\s)*(\r|\n|\r\n)/sm','',strip_tags($content)));

            $title = $this->_pageTitle;
        }

        require_once 'Maybe/Url.php';
        $url = new Maybe_Url();

        $db = $url->getDbAdapter();
        $db->beginTransaction();

        try {

            $url->addList($_SERVER['REQUEST_URI'], $title, $content);

        } catch ( Exception $e) {
            $db->rollBack();
            throw $e;
        }
        $db->commit();
    }
}
