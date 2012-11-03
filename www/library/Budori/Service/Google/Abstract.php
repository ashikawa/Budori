<?php
/**
 * @see Zend_Rest_Client
 */
require_once 'Zend/Rest/Client.php';

require_once 'Zend/Service/Exception.php';

require_once 'Budori/Oauth/Consumer.php';

abstract class Budori_Service_Google_Abstract extends Zend_Rest_Client
{

    /**
     * @var Budori_Oauth_Consumer
     */
    protected $_consumer	= null;

    protected $_uri			= "https://www.googleapis.com";

    protected $_service		= null;

    protected $_version		= null;

    public function __construct(Budori_Oauth_Consumer $consumer = null)
    {
        $uri = $this->_uri;

        parent::__construct($uri);

        if ( !is_null($consumer) ) {
            $this->setConsumer($consumer);
        }
    }

    public function setConsumer(Budori_Oauth_Consumer $consumer)
    {
        $this->_consumer = $consumer;
    }

    /**
     * Budori_Oauth_Consumer
     */
    public function getConsumer()
    {
        return $this->_consumer;
    }

    protected function _init()
    {
        $token	= $this->_consumer->getToken();

        $this->getHttpClient()
                ->setHeaders("Authorization", "OAuth " . $token->access_token);
    }

    protected function _assembleQuery($query, $version=null)
    {
        $service	= $this->_service;

        if ( is_null($version) ) {
            $version	= $this->_version;
        }

        return "/$service/$version/$query";
    }

    public function get($path, $query=null, $version=null)
    {
        $this->_init();
        $path = $this->_assembleQuery($path, $version);

        $response = $this->restGet($path, $query);

        if ( $response->getStatus() != 200) {
            throw new Zend_Service_Exception( $response->getMessage(), $response->getStatus() );
        }

        return json_decode($response->getBody(), true);
    }
}
