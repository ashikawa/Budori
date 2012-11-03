<?php
require_once 'Zend/Controller/Plugin/Abstract.php';

class Neri_Controller_Plugin_Profiler extends Zend_Controller_Plugin_Abstract
{

    protected $_viewScript	=  'inc/profiler.phtml';

    protected $_layoutName	= 'profile';

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        parent::postDispatch($request);

        if ( $request->isDispatched() ) {
            $this->_outputDisplay();
        }
    }

    protected function _outputDisplay()
    {
           require_once 'Zend/Controller/Action/HelperBroker.php';

        $layout = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');

        if ( $layout->isEnabled() ) {

            $view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;

            $profiles = $this->_getProfiler()
                            ->getQueryProfiles(null,true);

            if (!empty($profiles)) {
                $view->assign(
                    'profile', $profiles
                );

                $this->getResponse()->insert( $this->_layoutName, $view->render($this->_viewScript) );
            }
        }
    }

    protected function _outputLogger()
    {
        $profiles = $this->_getProfiler()
                        ->getQueryProfiles(null,true);

        $logger = Seal_Log::factory();

        if (!empty($profiles)) {
            foreach ($profiles as $key => $result) {
                $logger->info( $result->getQuery()
                            . " {" . var_export($result->getQueryParams(),true) . "} "
                            . "(" . $result->getElapsedSecs() ." msec)"
                        );
            }
        }
    }

    /**
     * @return Zend_Db_Profiler
     */
    protected function _getProfiler()
    {
        return Budori_Db::factory()->getProfiler();
    }
}
