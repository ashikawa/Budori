<?php
require_once 'Smarty.class.php';

/**
 * Smarty　のラッパー
 * 必要に応じて拡張
 *
 * @author shigeru.ashikawa
 * @copyright Copyright (c) 2011, infobahn inc.
 */
class Budori_Smarty extends Smarty
{
    /**
     * 不要な大域変数を削除
     */
    public function __construct()
    {
        parent::__construct();
        Smarty::$global_tpl_vars = array();
    }
}
