<?php
require_once 'Budori/Controller/Action.php';

/**
 * ヘルパーへのショートカットメソッドの設定。
 * コンテンツの初期化、基本設定。
 * Layoutを使う事が前提
 */
class Neri_Controller_Action_Http extends Budori_Controller_Action
{
    /**
     * プレディスパッチャ
     * 変数のアサイン
     */
    public function preDispatch()
    {
        parent::preDispatch();

        $request = $this->getRequest();

        $param = array_merge(
            $this->_getAllParams(),
            array(
                'module'		=> $request->getModuleName(),
                'action'		=> $request->getActionName(),
                'controller'	=> $request->getControllerName(),
            ));

        //パラメータはアクションメソッドでの上書きを許可するために、先に出力
        $this->view->assign($param);
    }

    /**
     * タイトルの追加
     * @param string $string
     */
    protected function prependTitle($string)
    {
        $this->view->getHelper('HeadTitle')
                        ->prepend($string);
    }

    /**
     * パンくずの追加
     * $target がリンク先になる(省略可)。
     *
     * @param string $string
     * @param string $target
     */
    protected function appendPankuzu($string,$target=null)
    {
        $this->view->getHelper('Pankuzu')
                ->append($string,$target);
    }

    /**
     * CSSファイルの追加。
     * コントローラとcssファイルを対応させたいので、ViewHelperから呼び出さずに init から設定するように。
     * @param string $path
     */
    protected function appendHeadLink($path)
    {
        $this->view->getHelper('HeadLink')
                        ->appendStylesheet($path);
    }

    /**
     * preDispatch()　以外からの呼び出し禁止
     * @see Neri_Controller_Action_Helper_LoginCheck()
     */
    protected function _loginCheck()
    {
        $this->_helper->LoginCheck();
    }
}
