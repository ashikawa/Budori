<?php
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * ログ出力ヘルパー
 * プラグインは、アクションコントローラの各メソッドの前に実行される
 * @todo Neri_Controller_Action_Helper_Logger　を自動登録するか悩み中
 */
class Neri_Controller_Plugin_Logger extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        parent::dispatchLoopStartup($request);
        $logger = $this->_getLogger();

        $logger->info("start request: " . urldecode($request->getRequestUri()));

        /**
         * @todo Budori_Debug 作って、呼び出したあと Sapi を元に戻す処理とか必要かもしんない。
         */
        Zend_Debug::setSapi('cli');
        $output = trim(Zend_Debug::dump($request->getParams(),"request params",false), PHP_EOL);

        $logger->info( $output );
    }

    public function dispatchLoopShutdown()
    {
        parent::dispatchLoopShutdown();;

        $this->_getLogger()->info("exit request");
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
