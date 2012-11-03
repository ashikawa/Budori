<?php
/**
 * Service ShortUrl
 */
class Budori_Service_ShortUrl
{
    /**
     * @var Zend_Cache_Core
     */
    protected $_cache = null;

    /**
     * short url services
     */
    protected $_services = array(
        "bit.ly",
        "t.co",
        "goo.gl",
    );

    /**
     * @todo change cache system
     */
    public function __construct()
    {}

    /**
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        if ( is_null($this->_cache) ) {
            require 'Budori/Cache.php';
            $this->_cache = Budori_Cache::factory('file');
        }

        return $this->_cache;
    }

    /**
     * @param Zend_Cache_Core $cache
     */
    public function setCache($cache)
    {
        $this->_cache = $cache;
    }

    /**
     * is valid short url service
     * @param string $url
     */
    public function isShortUrl($url)
    {
        $domain = parse_url($url, PHP_URL_HOST);

        return in_array($domain, $this->_services);
    }

    /**
     * get short url original
     *  file cache
     *  enable short url check
     *
     * @param string  $url
     * @param boolean $supportCheck
     */
    public function search($url, $supportCheck=true)
    {
        if ( $supportCheck && !$this->isShortUrl($url) ) {
            return $url;
        }

        $cacheName	= $this->_generateCacheName($url);
        $cache		= $this->getCache();

        if ( ($result = $cache->load($cacheName)) === false ) {
            $result = $this->request($url);

            $cache->save($result, $cacheName);
        }

        return $result;
    }

    /**
     * @param string $url
     */
    protected function _generateCacheName($url)
    {
        return __CLASS__ . md5($url);
    }

    /**
     * request http & get header
     * @param string $url
     */
    public function request( $url )
    {
        $headers = get_headers($url, 1);

        if ( isset($headers['Location']) ) {

            if ( is_array($headers['Location']) ) {
                return array_pop($headers['Location']); // last element
            } else {
                return $headers['Location'];
            }
        } else {
            return $url;
        }
    }
}
