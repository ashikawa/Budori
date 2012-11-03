<?php
/**
 * インクルードコンテンツ サイドバー
 * {zend_action }　での呼び出しが前提
 * _foward禁止
 */
class Inc_SideController extends Zend_Controller_Action
{

    /**
     * ログイン状態によってのテンプレート切り替え。
     * ログイン/ログアウト後のリダイレクト先の設定。
     */
    public function memberAction()
    {
        $redirect		= '';
        $redirectKey	= Neri_Const::AUTH_REDIRECT_KEY;

        if (isset($_GET[$redirectKey])) {
            $redirect	= $_GET[$redirectKey];
        } else {
            $redirect	= $this->getRequest()->getRequestUri();
        }

        $member		= Neri_Member::getInstance();

        $assigns = array(
            'member'		=> $member,
            'redirect'		=> $redirect,
            'redirectKey'	=> $redirectKey,
        );
        $this->view->assign($assigns);

        $renderer = $this->_helper->getHelper('viewRenderer');

        if ( !($renderer instanceof Zend_Controller_Action_Helper_ViewRenderer)) {
            throw new Budori_Exception('viewRenderer not found');
        }

        /**
         * @todo どっちの書き方が良いか検討中。
         * $this->render だと、その場ですぐにレンダリングされる。
         * viewRenderer 経由だと、 レンダリングは コントローラの処理終了後まで待つが、
         *　別アクションに委譲した時に上書きが必要になる。
         * (※inc_side は_foward()しても意味無いから関係ないかも)
         */
        if ( $member->isLogin() ) {
            $renderer->setScriptAction('member/prof');
//			$this->render('member/prof');
        } else {
            $renderer->setScriptAction('member/login');
//			$this->render('member/login');
        }
    }

    /**
     * 荒業。
     * 局所 Smarty タグの定義。
     */
    public function menuAction()
    {}

    public function versionAction()
    {
        $bootStrap = $this->getFrontController()
                            ->getParam('bootstrap',null);

        $env = null;

        if ($bootStrap instanceof Zend_Application_Bootstrap_BootstrapAbstract) {
            $env = $bootStrap->getEnvironment();
        }

        $assign = array(
            'php'	=> phpversion(),
            'zend'	=> Zend_Version::VERSION,
            'env'	=> $env,
        );

        $this->view->assign($assign);
    }

    public function twitterAction()
    {
        $assigns = array(
            'users'			=> "m_s_modified",
            'profileImage'	=> "http://a3.twimg.com/profile_images/575934935/20095212000019_normal.jpg",
        );

        $this->view->assign($assigns);
    }

    public function searchAction()
    {}
}
