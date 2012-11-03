<?php

class Budori_Oauth_Token
{

    const ACCESS_TOKEN	= "access_token";

    const EXPIRES_IN	= "expires_in";

    const REFRESH_TOKEN	= "refresh_token";

    const SCOPE			= "scope";

    const TOKEN_TYPE	= "token_type";

    const CREATED_AT	= "created_at";

    protected $_data = array();

    public function __construct( Zend_Http_Response $response = null )
    {
        if ( !is_null($response) ) {
            $body = json_decode($response->getBody());
            $this->setParams($body);
        }
    }

    public function setParams($data)
    {
        if ($data instanceof Budori_Oauth_Token) {
            $data = $data->getParams();
        }

        foreach ($data as $_key => $_value) {
            $this->setParam($_key, $_value);
        }

        return $this;
    }

    public function getParams()
    {
        return $this->_data;
    }

    public function getParam($key)
    {
        if ( isset($this->_data[$key]) ) {
            return $this->_data[$key];
        }

        return null;
    }

    public function setParam($key, $value)
    {
        $this->_data[$key]	= $value;

        return $this;
    }

    public function getAccessToken()
    {
        return $this->getParam(self::ACCESS_TOKEN);
    }

    public function setAccessToken($value)
    {
        return $this->setParam(self::ACCESS_TOKEN, $value);
    }

    public function getExpiresIn()
    {
        return $this->getParam(self::EXPIRES_IN);
    }

    public function setExpiresIn($value)
    {
        return $this->setParam(self::EXPIRES_IN, $value);
    }

    public function getRefreshToken()
    {
        return $this->getParam(self::REFRESH_TOKEN);
    }

    public function setRefreshToken($value)
    {
        return $this->setParam(self::REFRESH_TOKEN, $value);
    }

    public function getScope()
    {
        return $this->getParam(self::SCOPE);
    }

    public function setScope($value)
    {
        return $this->setParam(self::SCOPE, $value);
    }

    public function getTokeType()
    {
        return $this->getParam(self::TOKEN_TYPE);
    }

    public function setTokenType($value)
    {
        return $this->setParam(self::TOKEN_TYPE, $value);
    }

    public function getCreated()
    {
        return $this->getParam(self::CREATED_AT);
    }

    public function setCreated($value)
    {
        return $this->setParam(self::CREATED_AT, $value);
    }

    public function __get($key)
    {
        return $this->getParam($key);
    }
    public function __set($key, $value)
    {
        $this->setParam($key, $value);
    }
}
