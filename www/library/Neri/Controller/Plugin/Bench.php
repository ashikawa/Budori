<?php
require_once 'Benchmark/Timer.php';
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * ベンチマーク測定プラグイン
 */
class Neri_Controller_Plugin_Bench extends Zend_Controller_Plugin_Abstract
{

    /**
     * Benchmark_Timer
     * @var Benchmark_Timer
     */
    protected $_bench;

    /**
     * Enter description here...
     * @param Benchmark_Timer $bench
     */
    public function __construct( Benchmark_Timer $bench = null )
    {
        if ($bench instanceof Benchmark_Timer) {
            $this->_bench = $bench;
        } else {
            $this->_bench = new Benchmark_Timer();
        }

        $this->_bench->start();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        parent::preDispatch($request);

        $controller = ucwords($request->getControllerName());
        $this->_bench->setMarker("$controller::preDispatch()");
    }

    /**
     * @todo 画面に出力するなら postDispatch() だが、ログに出すなら　dispatchLoopShutdown()
     * 本番では画面に出さずにログなのでとりあえず放置。
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        parent::postDispatch($request);

        $controller = ucwords($request->getControllerName());
        $this->_bench->setMarker("$controller::postDispatch()");

        // Return early if forward detected
        if ( $request->isDispatched() ) {
            $this->_output();
        }
    }

    /**
     * 出力用メソッド
     */
    protected function _output()
    {
           require_once 'Zend/Controller/Action/HelperBroker.php';

        $layout = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');
        if ( $layout->isEnabled() ) {

            $view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;

            $assign = array(
                'bench' => $this->_bench,
                'total' => $this->_bench->timeElapsed(),
            );

            $view->assign($assign);
            $this->getResponse()->insert('bench', $view->render('inc/bench.phtml'));
        }
    }

    public function dispatchLoopShutDown()
    {
        parent::dispatchLoopShutdown();

        $log = $this->_getLogger();
        $log->info("usage:" . number_format(memory_get_usage())." byte");
    }

    /**
     * @return Zend_Log
     */
    protected function _getLogger()
    {
        require_once 'Budori/Log.php';

        return Budori_Log::factory();
    }
}
