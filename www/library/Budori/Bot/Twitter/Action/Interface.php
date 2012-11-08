<?php
interface Budori_Bot_Twitter_Action_Interface
{
    /**
     * 処理実行前に一度だけ呼ばれる
     */
    public function init();

    /**
     * 検索したツイート、ループ内で呼ばれる
     */
    public function run($tweet);
}
