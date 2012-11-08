<?php
/**
 * Budori_Bot_Twitter_Crawler_Interface
 * クローラーを反応させるトリガー
 */
interface Budori_Bot_Twitter_Crawler_Interface
{
    /**
     * ツイートの配列
     * Twitter API の response['results']
     * @return array.<array>
     */
    public function search();

    /**
     * @param string $id 前回検索したツイートの'id_str'
     */
    public function setSinceId($id);

    /**
     * @return string
     */
    public function getSinceId();
}
