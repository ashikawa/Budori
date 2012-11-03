<?php
/**
 * 通常のログイン認証用コントローラ
 * 認証メッセージは細かい条件分岐をするのならテンプレートに書いても良いかも...
 */
class AuthController extends Neri_Controller_Action_Http
{

    protected $_resultMessages	= array(
        Zend_Auth_Result::FAILURE						=> 'ID、またはパスワードが間違っています',
        Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND	=> 'ID、またはパスワードが間違っています',
        Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS	=> 'ID、またはパスワードが間違っています',
        Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID	=> 'ID、またはパスワードが間違っています',
        Zend_Auth_Result::FAILURE_UNCATEGORIZED			=> 'ID、またはパスワードが間違っています',
        Zend_Auth_Result::SUCCESS						=> '認証に成功しました',
    );

    /**
     * Enter description here...
     * @var Neri_Member
     */
    protected $_member;

    /**
     * ログイン/アウト後リダイレクト先
     * @var string
     */
    protected $_redirectUrl;

    public function init()
    {
        parent::init();
        $this->_member = Neri_Member::getInstance();

        $key = Neri_Const::AUTH_REDIRECT_KEY;
        $this->_redirectUrl = urldecode($this->_getParam($key,'/'));
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $this->prependTitle('ログイン');
        $this->appendPankuzu('ログイン','/' . $this->getRequest()->getActionName());
    }

    public function indexAction()
    {}

    public function loginAction()
    {

        $params = array_merge(
            array(
                'id'		=> '',
                'password'	=> '',
            ),$this->_getAllParams());

        $db = Budori_Db::factory();

        $id		= $params['id'];
        $pass 	= $params['password'];

        //もう少し複雑にするなら Zend_Validateへ。
        if ($id === '') {
            $this->view->assign('message', 'IDを入力してください');

            return $this->_forward('index');
        }

        $auth = new Neri_Auth_Adapter_DbTable($db);

        $auth->setIdentity($id)
                ->setCredential( $pass );

        $result	= $this->_member->login($auth);

        if ( !$result->isValid() ) {

            //$this->view->assign('message', $result->getMessages() );
            $this->view->assign('message', $this->_getResultMessage($result));

            return $this->_forward('index');
        }

        return $this->_redirect($this->_redirectUrl);
    }

    public function logoutAction()
    {
        $this->_member->logout();

        return $this->_redirect($this->_redirectUrl);
        //return $this->_forward('index');
    }

    protected function _getResultMessage( $code )
    {

        if ($code instanceof Zend_Auth_Result) {
            $code = $code->getCode();
        }

        return $this->_resultMessages[$code];
    }
}
