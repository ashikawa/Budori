<?php

/**
 * メンバークラス用インターフェース
 */
interface Budori_Member_Interface
{

    /**
     * ログイン済みかどうか取得
     * @return boolean
     */
    public function isLogin();

    /**
     * IDの取得
     */
    public function getIdentity();

    /**
     * ログイン処理
     * @param  Zend_Auth_Adapter_Interface $adapter
     * @return Zend_Auth_Result
     */
    public function login( Zend_Auth_Adapter_Interface $adapter );

    /**
     * ログアウト処理
     */
    public function logout();

    /**
     * 保存
     */
    public function save();

    /**
     * メンバ配列の所得
     */
    public function toArray();
}
