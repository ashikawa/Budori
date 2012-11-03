<?php
require_once 'facebook/base_facebook.php';

class Budori_Service_Facebook extends BaseFacebook
{
    /**
     * @var Zend_Session_Namespace
     */
    protected $_session = null;

    /**
     * @var array
     */
    protected $_kSupportedKeys =	array('state', 'code', 'access_token', 'user_id');

    /**
     * @return Zend_Session_Namespace
     */
    public function getSession()
    {
        if ( is_null($this->_session) ) {
            $this->_session = new Zend_Session_Namespace( __CLASS__ );
        }

        return $this->_session;
    }

    /**
     * @param string $key
     * @param mixied $value
     */
    protected function setPersistentData($key, $value)
    {
        if ( !$this->_isAllowedKey($key) ) {
            self::errorLog('Unsupported key passed to setPersistentData.');

            return;
        }

        $session = $this->getSession();
        $session->$key = $value;
    }

    /**
     *
     * @param  string $key
     * @param  mixied $default
     * @return mixied
     */
    protected function getPersistentData($key, $default = false)
    {
        if ( !$this->_isAllowedKey($key) ) {
            self::errorLog('Unsupported key passed to setPersistentData.');

            return $default;
        }

        $session = $this->getSession();

        if ( isset($session->$key) ) {
            return $session->$key;
        }

        return $default;
    }

    /**
     * @param string $key
     */
    protected function clearPersistentData($key)
    {
        if ( !$this->_isAllowedKey($key) ) {
            self::errorLog('Unsupported key passed to clearPersistentData.');

            return;
        }

        $session = $this->getSession();
        unset($session->$key);
    }

    protected function clearAllPersistentData()
    {
        if (!is_null($this->_session)) {
            $this->getSession()->unsetAll();
        }
    }

    /**
     * @param  string  $key
     * @return boolean
     */
    protected function _isAllowedKey($key)
    {
        return in_array($key, $this->_kSupportedKeys);
    }
}
