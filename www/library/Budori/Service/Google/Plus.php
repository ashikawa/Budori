<?php
/**
 * @see Zend_Rest_Client
 */
require_once 'Zend/Rest/Client.php';

/**
 * @see Zend_Rest_Client_Result
 */
require_once 'Zend/Rest/Client/Result.php';

/**
 * @see Zend_Oauth_Consumer
 */
require_once 'Zend/Oauth/Consumer.php';


class Budori_Service_Google_Plus extends Zend_Rest_Client
{
	
    /**
     * OAuth Endpoint
     */
    const OAUTH_BASE_URI = 'http://twitter.com/oauth';

    /**
     * @var Zend_Http_CookieJar
     */
    protected $_cookieJar;

    /**
     * Current method type (for method proxying)
     *
     * @var string
     */
    protected $_methodType;

    /**
     * Zend_Oauth Consumer
     *
     * @var Zend_Oauth_Consumer
     */
    protected $_oauthConsumer = null;

    /**
     * Options passed to constructor
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Local HTTP Client cloned from statically set client
     *
     * @var Zend_Http_Client
     */
    protected $_localHttpClient = null;

    /**
     * Constructor
     *
     * @param  array $options Optional options array
     * @return void
     */
    public function __construct($options = null, Zend_Oauth_Consumer $consumer = null)
    {
        $this->setUri('https://www.googleapis.com/plus/v1/');
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (!is_array($options)) {
            $options = array();
        }
        $options['siteUrl'] = self::OAUTH_BASE_URI;

        $this->_options = $options;
        
        if (isset($options['accessToken'])
	        && $options['accessToken'] instanceof Zend_Oauth_Token_Access) {
            $this->setLocalHttpClient($options['accessToken']->getHttpClient($options));
        } else {
            $this->setLocalHttpClient(clone self::getHttpClient());
            if ($consumer === null) {
                $this->_oauthConsumer = new Zend_Oauth_Consumer($options);
            } else {
                $this->_oauthConsumer = $consumer;
            }
        }
    }

    /**
     * Set local HTTP client as distinct from the static HTTP client
     * as inherited from Zend_Rest_Client.
     *
     * @param Zend_Http_Client $client
     * @return self
     */
    public function setLocalHttpClient(Zend_Http_Client $client)
    {
        $this->_localHttpClient = $client;
        $this->_localHttpClient->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');
        return $this;
    }

    /**
     * Get the local HTTP client as distinct from the static HTTP client
     * inherited from Zend_Rest_Client
     *
     * @return Zend_Http_Client
     */
    public function getLocalHttpClient()
    {
        return $this->_localHttpClient;
    }

    /**
     * Checks for an authorised state
     *
     * @return bool
     */
    public function isAuthorised()
    {
        if ($this->getLocalHttpClient() instanceof Zend_Oauth_Client) {
            return true;
        }
        return false;
    }

    /**
     * Proxy service methods
     *
     * @param  string $type
     * @return Zend_Service_Twitter
     * @throws Zend_Service_Twitter_Exception If method not in method types list
     */
    public function __get($type)
    {
        $this->_methodType = $type;
        return $this;
    }

    /**
     * Method overloading
     *
     * @param  string $method
     * @param  array $params
     * @return mixed
     * @throws Zend_Service_Twitter_Exception if unable to find method
     */
    public function __call($method, $params)
    {
        if (method_exists($this->_oauthConsumer, $method)) {
            $return = call_user_func_array(array($this->_oauthConsumer, $method), $params);
            if ($return instanceof Zend_Oauth_Token_Access) {
                $this->setLocalHttpClient($return->getHttpClient($this->_options));
            }
            return $return;
        }
        if (empty($this->_methodType)) {
            include_once 'Zend/Service/Twitter/Exception.php';
            throw new Zend_Service_Twitter_Exception(
                'Invalid method "' . $method . '"'
            );
        }
        $test = $this->_methodType . ucfirst($method);
        if (!method_exists($this, $test)) {
            include_once 'Zend/Service/Twitter/Exception.php';
            throw new Zend_Service_Twitter_Exception(
                'Invalid method "' . $test . '"'
            );
        }

        return call_user_func_array(array($this, $test), $params);
    }

    /**
     * Initialize HTTP authentication
     *
     * @return void
     */
    protected function _init()
    {
        if (!$this->isAuthorised() && $this->getUsername() !== null) {
            require_once 'Zend/Service/Twitter/Exception.php';
            throw new Zend_Service_Twitter_Exception(
                'Twitter session is unauthorised. You need to initialize '
                . 'Zend_Service_Twitter with an OAuth Access Token or use '
                . 'its OAuth functionality to obtain an Access Token before '
                . 'attempting any API actions that require authorisation'
            );
        }
        $client = $this->_localHttpClient;
        $client->resetParameters();
        if (null == $this->_cookieJar) {
            $client->setCookieJar();
            $this->_cookieJar = $client->getCookieJar();
        } else {
            $client->setCookieJar($this->_cookieJar);
        }
    }

    

    /**
     * Protected function to validate that the integer is valid or return a 0
     * @param mixed $int
     * @return integer
     */
    protected function _validInteger($int)
    {
        if (preg_match("/(\d+)/", $int)) {
            return $int;
        }
        return 0;
    }


    /**
     * Call a remote REST web service URI and return the Zend_Http_Response object
     *
     * @param  string $path            The path to append to the URI
     * @throws Zend_Rest_Client_Exception
     * @return void
     */
    protected function _prepare($path)
    {
        // Get the URI object and configure it
        if (!$this->_uri instanceof Zend_Uri_Http) {
            require_once 'Zend/Rest/Client/Exception.php';
            throw new Zend_Rest_Client_Exception(
                'URI object must be set before performing call'
            );
        }

        $uri = $this->_uri->getUri();

        if ($path[0] != '/' && $uri[strlen($uri) - 1] != '/') {
            $path = '/' . $path;
        }

        $this->_uri->setPath($path);

        /**
         * Get the HTTP client and configure it for the endpoint URI.
         * Do this each time because the Zend_Http_Client instance is shared
         * among all Zend_Service_Abstract subclasses.
         */
        $this->_localHttpClient->resetParameters()->setUri((string) $this->_uri);
    }

    /**
     * Performs an HTTP GET request to the $path.
     *
     * @param string $path
     * @param array  $query Array of GET parameters
     * @throws Zend_Http_Client_Exception
     * @return Zend_Http_Response
     */
    protected function _get($path, array $query = null)
    {
        $this->_prepare($path);
        $this->_localHttpClient->setParameterGet($query);
        return $this->_localHttpClient->request(Zend_Http_Client::GET);
    }

    /**
     * Performs an HTTP POST request to $path.
     *
     * @param string $path
     * @param mixed $data Raw data to send
     * @throws Zend_Http_Client_Exception
     * @return Zend_Http_Response
     */
    protected function _post($path, $data = null)
    {
        $this->_prepare($path);
        return $this->_performPost(Zend_Http_Client::POST, $data);
    }

    /**
     * Perform a POST or PUT
     *
     * Performs a POST or PUT request. Any data provided is set in the HTTP
     * client. String data is pushed in as raw POST data; array or object data
     * is pushed in as POST parameters.
     *
     * @param mixed $method
     * @param mixed $data
     * @return Zend_Http_Response
     */
    protected function _performPost($method, $data = null)
    {
        $client = $this->_localHttpClient;
        if (is_string($data)) {
            $client->setRawData($data);
        } elseif (is_array($data) || is_object($data)) {
            $client->setParameterPost((array) $data);
        }
        return $client->request($method);
    }

}
