<?php
require_once 'Zend/Controller/Router/Abstract.php';
require_once 'Zend/Console/Getopt.php';

class Budori_Controller_Router_Cli extends Zend_Controller_Router_Abstract
{
    /**
     * @var Zend_Console_Getopt
     */
    protected $_getopt = null;

    protected $_rules = array(
                'action|a=s'		=> 'action',
                'controller|c=s'	=> 'controller',
                'module|m=s'		=> 'module',
            );

    /**
     * Constructor
     *
     * @param  array $params
     * @return void
     */
    public function __construct( $params = array() )
    {
        parent::__construct($params);
        $this->_getopt = new Zend_Console_Getopt( $this->_rules );
    }

    /**
     * Generates a URL path that can be used in URL creation, redirection, etc.
     *
     * May be passed user params to override ones from URI, Request or even defaults.
     * If passed parameter has a value of null, it's URL variable will be reset to
     * default.
     *
     * If null is passed as a route name assemble will use the current Route or 'default'
     * if current is not yet set.
     *
     * Reset is used to signal that all parameters should be reset to it's defaults.
     * Ignoring all URL specified values. User specified params still get precedence.
     *
     * Encode tells to url encode resulting path parts.
     *
     * @param  array                            $userParams Options passed by a user used to override parameters
     * @param  mixed                            $name       The name of a Route to use
     * @param  bool                             $reset      Whether to reset to the route defaults ignoring URL params
     * @param  bool                             $encode     Tells to encode URL parts on output
     * @throws Zend_Controller_Router_Exception
     * @return string                           Resulting URL path
     */
    public function assemble($userParams, $name = null, $reset = false, $encode = true)
    {
        return $this->_getopt->toString();
    }

    /**
     * Processes a request and sets its controller and action.  If
     * no route was possible, an exception is thrown.
     *
     * @param  Zend_Controller_Request_Abstract
     * @throws Zend_Controller_Router_Exception
     * @return Zend_Controller_Request_Abstract|boolean
     */
    public function route(Zend_Controller_Request_Abstract $dispatcher)
    {
        $opt = $this->_getopt;

        $opt->setOptions($this->getParams());

        $dispatcher->setControllerName($opt->controller)
                ->setActionName($opt->action)
                ->setModuleName($opt->module)
                ->setParams($opt->getRemainingArgs());

        return $dispatcher;
    }
}
