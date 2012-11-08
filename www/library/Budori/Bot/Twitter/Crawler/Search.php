<?php
require_once 'Zend/Service/Twitter/Search.php';
require_once 'Budori/Bot/Twitter/Crawler/Interface.php';

/**
 * Budori_Bot_Twitter_Crawler_Search
 * Twitter検索APIからレスポンスを返す対象ツイートを取得
 */
class Budori_Bot_Twitter_Crawler_Search implements Budori_Bot_Twitter_Crawler_Interface
{

    /**
     * @var string 検索クエリ
     */
    protected $_query = null;

    /**
     * @var Zend_Service_Twitter_Search
     */
    protected $_search = null;

    /**
     * @var string
     */
    protected $_siceId = null;

    /**
     * @var int
     */
    protected $_rpp = 100;

    public function __construct()
    {
        $this->_search  = new Zend_Service_Twitter_Search();
    }

    /**
     * @param  string               $id
     * @return OysterBot_ListSearch
     */
    public function setSinceId($id)
    {
        $this->_siceId = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getSinceId()
    {
        return $this->_siceId;
    }

    /**
     * 検索ワードのセット
     * @param  string               $query
     * @return OysterBot_ListSearch
     */
    public function setQuery($query)
    {
        $this->_query = $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * リプライ対象のtweetのリスト
     * @return array.<array>
     */
    public function search()
    {
        $results    = array();
        $resultsTmp = array();
        $query      = $this->_query;
        $page       = 1;
        $rpp        = $this->_rpp;

        $params     = array(
            'rpp'       => $rpp,
        );

        $sinceId    = $this->getSinceId();

        if ( !is_null($sinceId) ) {
            $params['since_id']  = $sinceId;
        }

        do {

            $params['page'] = $page;

            $responce   = $this->_callApi($query, $params);

            $resultsTmp = $responce['results'];

            $results    = array_merge($results, $resultsTmp);
            $page++;

            //検索結果なし
            if (count($resultsTmp) == 0) {
                break;
            }

            if ($page > 10) { // 安全装置
                break;
            }

            // 次のページがあるか判断してループ
        } while (count($resultsTmp) == $rpp);

        // 古い物を先頭にする
        // since_id で検索しているため、処理が途中で中断しても良いように
        return $responce['results'];
    }

    /**
     * @return array twitterApi resopnse
     */
    protected function _callApi($query, $params)
    {
        return $this->_search->search($query, $params);
    }
}
