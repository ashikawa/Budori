<?php
/**
 * 定数クラス
 * アプリ全体で共通のパラメータ、その他。
 * ※ここで定義する物は最低限にすること。
 */
class Neri_Const
{
    private function __construct()
    {}

    /**
     * ログイン、ログアウト後のりダイレクト、　GETパラメータ。
     */
    const AUTH_REDIRECT_KEY = 'r';
}
