<?php
class Mail_Address
{
    protected $_address	= null;

    protected $_name	= null;

    protected $_options = array();

    public function __construct( $address, $name = null, $options = array() )
    {
        if (is_null($address) && !is_string($address)) {
            throw new Budori_Exception('$address must string');
        }

        $this->_address	= $address;

        $this->_name	= $name;

        $this->_options = $options;
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getOptions()
    {
        return $this->_options;
    }
}
