<?php
require_once 'Zend/Service/Twitter.php';
require_once 'Budori/Bot/Twitter/Crawler/Interface.php';

/**
 * Budori_Bot_Twitter
 *  Crawler から所得したメッセージに対して、Actionを実行する
 */
class Budori_Bot_Twitter
{
    protected $_cacheName = "Budori_BOT_TWITTER_SINCE_ID";

    /**
     * @var Budori_Bot_Twitter_Crawler_Interface
     */
    protected $_crawler = null;

    /**
     * @var Budori_Bot_Twitter_Action_Interface
     */
    protected $_action = null;

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache   = null;

    /**
     * @param  Budori_Bot_Twitter_Crawler_Interface $crawler
     * @return Budori_Bot_Twitter
     */
    public function setCrawler(Budori_Bot_Twitter_Crawler_Interface $crawler)
    {
        $this->_crawler = $crawler;

        return $this;
    }

    /**
     * @return Budori_Bot_Twitter_Crawler_Interface
     */
    public function getCrawler()
    {
        return $this->_crawler;
    }

    /**
     * @param  Budori_Bot_Twitter_Action_Interface $action
     * @return Budori_Bot_Twitter
     */
    public function setAction(Budori_Bot_Twitter_Action_Interface $action)
    {
        $this->_action = $action;

        return $this;
    }

    /**
     * @return Budori_Bot_Twitter_Action_Interface
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @param  Zend_Cache_Core    $cache
     * @return Budori_Bot_Twitter
     */
    public function setCache(Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;

        return $this;
    }

    /**
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * 処理の実行
     */
    public function run()
    {
        $sinceId = $this->loadSinceId();

        $search  = $this->getCrawler();
        $search->setSinceId($sinceId);

        $tweets  = $search->search();

        // SinceId を使っているので、途中でエラー中断しても復帰できるように、
        // 古い物から返信する
        $tweets  = array_reverse($tweets);

        $this->_action->init();

        foreach ($tweets as $tweet) {

            try {

                $this->_action->run($tweet);

                $sinceId = $tweet['id_str'];

            } catch (Exception $e) {

                $this->saveSinceId($sinceId);
                throw $e;
            }
        }

        $this->saveSinceId($sinceId);

        return ;
    }

    /**
     * @return boolean True if no problem
     */
    public function saveSinceId($id)
    {
        $cache = $this->getCache();

        return $cache->save($id, $this->_cacheName);
    }

    /**
     * @return string
     */
    public function loadSinceId()
    {
        $cache = $this->getCache();

        if ( ( $sinceId = $cache->load( $this->_cacheName ) ) === false ) {
            return null;
        }

        return $sinceId;
    }

    /**
     * @return string
     */
    protected function _buildeMessage($tweet)
    {
        return $this->_builder->build($tweet);
    }

}
