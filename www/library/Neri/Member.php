<?php
require_once 'Zend/Auth/Adapter/Interface.php';
require_once 'Budori/Member/Interface.php';

/**
 * 会員のログイン状態と基本的なプロパティを管理する。
 */
class Neri_Member implements Budori_Member_Interface
{

    /**
     * Singleton instance
     *
     * @var Neri_Member
     */
    protected static $_instance = null;

    /**
     * auth object
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * Enter description here...
     *
     * @var Neri_Member_Profile
     */
    protected $_profile;

    /**
     * コンストラクタ
     * Singleton
     */
    protected function __construct()
    {
        require_once 'Zend/Auth.php';
        $this->_auth = Zend_Auth::getInstance();

        $this->_initProfile();
    }

    /**
     * Singleton pattern implementation
     * @return Budori_Member_Interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * ログイン済みフラグ
     * @return boolean
     */
    public function isLogin()
    {
        return $this->_auth->hasIdentity();
    }

    /**
     * メンバーIDの取得
     */
    public function getIdentity()
    {
        return $this->_auth->getIdentity();
    }

    /**
     * ログイン処理
     * @param  Zend_Auth_Adapter_Interface $adapter
     * @return Zend_Auth_Result
     */
    public function login( Zend_Auth_Adapter_Interface $adapter )
    {
        $reuslt = $this->_auth->authenticate($adapter);

        if ( $reuslt->isValid() ) {

            $this->_initProfile();

        } else {

            $this->logout();
        }

        return $reuslt;
    }

    /**
     * Budori_Member_Exception はセッションの情報とDBの情報が食い違った場合に発生。
     * ここで処理しないと、セッションが切れるまでエラー画面しか見れなくなる。
     */
    protected function _initProfile()
    {
        if ( $this->_auth->hasIdentity() ) {

            require_once 'Neri/Member/Profile.php';

            try {
                $this->_profile = new Neri_Member_Profile( $this->_auth->getIdentity() );
            } catch (Exception $e) {
                $this->logout();
                throw $e;
            }
        }
    }

    /**
     * ログアウト処理
     */
    public function logout()
    {
        $this->_auth->clearIdentity();
        $this->_profile = null;
    }

    /**
     * save member propaty
     */
    public function save()
    {
        $this->_checkLogined();
        $this->_profile->save();
    }

    /**
     * メンバ配列の取得
     * @return array
     */
    public function toArray()
    {
        $this->_checkLogined();

        return $this->_profile->toArray();
    }

    /**
     * メンバープロパティの取得
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        $this->_checkLogined();

        return $this->_profile->$name;
    }

    /**
     * メンバープロパティの設定
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name,$value)
    {
        $this->_checkLogined();
        $this->_profile->$name = $value;
    }

    /**
     * Enforce singleton; disallow cloning
     */
    private function __clone()
    {}

    protected function _checkLogined()
    {
        if ( !$this->isLogin()) {
            throw new Budori_Member_Exception("not found auth data");
        }
    }
}
