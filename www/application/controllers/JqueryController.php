<?php
/**
 * jQuery 各種動作確認コントローラ
 */
class JqueryController extends Neri_Controller_Action_Http
{
    const JQUERY_STYLE_SHEET_PATH = '/style/content/jquery.css';

    protected $_actions = array(
        "accordion",
        "apply",
        "calendar",
//		"checkgroup",
        "curry",
        "data",
        "disable",
        "dpassword",
        "options",
        "plugin",
        "prototype",
        "resize",
        "sandbox",
        "sortable",
        "thickbox",
        "tile",
        "twitter",
        "zip",
        "cssmenu",
        "address",
        "strage",
        "osm",
    );

    public function init()
    {
        parent::init();
        $this->appendHeadLink(self::JQUERY_STYLE_SHEET_PATH);
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $this->prependTitle('jQquery');
        $this->appendPankuzu('jQuery','/' . $this->getRequest()->getControllerName() );
    }

    public function indexAction()
    {}

    public function checkgroupAction()
    {
        $this->prependTitle('checkgroup');
        $this->appendPankuzu('checkgroup');

        $params = array_merge_recursive(
            array(
                'item' => array('1' => false, '2' => false, '3' => false,),
            ),$this->_getAllParams()
        );

        var_export( $params );
    }

    public function autocompleteAction()
    {
        $this->prependTitle('autocomplete');
        $this->appendPankuzu('autocomplete');

        $this->view->assign('domains', Budori_Mobile::getDomains()->toArray());
    }

    public function __call($methodName,$args)
    {
        if ('Action' == substr($methodName, -6)) {
            $action = substr($methodName, 0, strlen($methodName) - 6);

            if ( in_array($action, $this->_actions )) {
                return $this->_defaultAction($action);
            }
        }

        parent::__call($methodName, $args);
    }

    protected function _defaultAction($name)
    {
        $this->prependTitle($name);
        $this->appendPankuzu($name);
    }
}
