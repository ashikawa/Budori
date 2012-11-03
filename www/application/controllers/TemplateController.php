<?php

/**
 * Zend_Form() の使用例。
 * 便利だがデザインの汎用性が低い。
 */
class TemplateController extends Neri_Controller_Action_Http
{

    /**
     * Enter description here...
     * @var Zend_Form
     */
    protected static $_form = null;

    protected $_configName;

    public function init()
    {
        parent::init();
        $this->appendHeadLink('/style/content/template.css');
    }

    public function preDispatch()
    {
        parent::preDispatch();
        $this->prependTitle('template');
        $this->appendPankuzu('template','/' . $this->getRequest()->getControllerName() );

//		$configName = $this->_getParam('config',null);
//		if (is_null($configName)) {
//			throw new Zend_Controller_Action_Exception('no routing', 404);
//		}
//
//		$this->_configName = $configName;

        $this->_configName = 'template';
    }

    public function indexAction()
    {
        $form = $this->_getForm();

        $form->setDefaults($this->_getAllParams());

        $this->view->assign('form', $form);
    }

    public function confAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->_forward('index');
        }

        $form = $this->_getForm();

        if (!$form->isValid($this->_getAllParams())) {
            return $this->_forward('index');
        }

        $decorator = new FormProcesser($form);

        $form = $decorator->buildConfigPage();

        Zend_Debug::dump($this->_getAllParams());

        $this->view->assign('form', $form);
    }

    public function endAction()
    {
        $this->disableLayout();
        $this->setNoRender();

        $form = $this->_getForm();

        $params = $this->_getAllParams();

        if ( !$form->valid($params) ) {
            throw new Zend_Exception('wrong paramater');
        }

        $form->setDefaults($params);

        $decorator = new FormProcesser($form);

        $str = $decorator->buildMessageBody();

        $this->getResponse()
            ->setHeader('Content-Type','text/plain')
            ->setBody($str);
    }

    /**
     * @return Zend_Form
     */
    protected function _getForm()
    {
        if (is_null(self::$_form)) {
            self::$_form = $this->_createForm();
        }

        return self::$_form;
    }

    /**
     * Enter description here...
     * @return Zend_Form
     * @link http://framework.zend.com/manual/ja/zend.form.quickstart.html
     */
    protected function _createForm()
    {
        $configName = $this->_configName;

        try {
            $config = Budori_Config::factory("form/$configName.ini","form");
        } catch (Zend_Config_Exception $e) {
            throw new Zend_Controller_Action_Exception($e->getMessage(), 404, $e);
        }

        $form = new Zend_Form($config->toArray());

        return $form;
    }

}
