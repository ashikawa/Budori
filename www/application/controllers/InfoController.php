<?php
/**
 * テンプレートの自動レンダリング用コントローラ
 * @todo 自動レンダリング時のViewHelperの操作が課題。
 */
class InfoController extends Neri_Controller_Action_Http
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->prependTitle('info');
        $this->appendPankuzu('info','/' . $this->getRequest()->getControllerName() );
    }

    /**
     * php info 出力結果
     */
    public function phpinfoAction()
    {
        $this->disableLayout();
        $this->setNoRender();

        phpinfo();
    }

    public function gdinfoAction()
    {
        $this->view->assign('gdinfo',gd_info());
    }

    /**
     * 「テンプレートが見つかりません」例外を「ページが見つかりません」例外に変換。
     * @todo 例外翻訳が若干くどい。
     *
     * @param string $action
     */
    public function dispatch($action)
    {
        try {
            parent::dispatch($action);
        } catch (Zend_View_Exception $e) {
            throw new Zend_Controller_Action_Exception($e->getMessage(),404,$e);
        } catch (SmartyException $e) {
            throw new Zend_Controller_Action_Exception($e->getMessage(),404,$e);
        }
    }

    /**
     * 例外を投げずにレンダリング
     *
     * @param string $methodName
     * @param array  $args
     */
    public function __call($methodName, $args)
    {
        if ('Action' != substr($methodName, -6)) {
            parent::__call( $methodName, $args );
        }
    }
}
