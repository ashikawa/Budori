<?php
require_once 'Zend/Controller/Request/Http.php';

/** @see Zend_Uri */
require_once 'Zend/Uri.php';

/**
 * Budori_Controller_Request_Proxy
 */
class Budori_Controller_Request_Proxy extends Zend_Controller_Request_Http
{

    /**
     * Is https secure request
     *
     * @return boolean
     */
    public function isSecure()
    {
        return (prent::isSecure()
            || $this->getServer('X_FORWARDED_PROTO') === parent::SCHEME_HTTPS);
    }
}
