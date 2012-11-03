<?php
require_once 'Zend/Validate/Abstract.php';

/**
 * 同値チェック
 */
class Budori_Validate_StringEquals extends Zend_Validate_Abstract
{

    const NOT_EQUALS = 'notEquals';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EQUALS => "'%str1%' is not equals '%str2%' ",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'str1' => '_str1',
        'str2' => '_str2'
    );

    protected $_str1;

    protected $_str2;

    /**
     * Sets validator options
     *
     * @param  string $str
     * @return void
     */
    public function __construct()
    {}

    /**
     * Returns the str option
     *
     * @return string
     */
    public function getStr1()
    {
        return $this->_str1;
    }

    /**
     * Sets the str option
     *
     * @param  string                     $str
     * @return Zend_Validate_StringLength Provides a fluent interface
     */
    public function setStr1($str1)
    {
        $this->_str1 = (string) $str1;

        return $this;
    }

    /**
     * Returns the str option
     *
     * @return string
     */
    public function getStr2()
    {
        return $this->_str2;
    }

    /**
     * Sets the str option
     *
     * @param  string                     $str
     * @return Zend_Validate_StringLength Provides a fluent interface
     */
    public function setStr2($str2)
    {
        $this->_str2 = (string) $str2;

        return $this;
    }

    /**
     * 同値チェック
     * @param  string  $value
     * @return boolean
     */
    public function isValid( $value )
    {
        $this->_str1 = array_shift($value);
        $this->_str2 = array_shift($value);

        if ($this->_str1 != $this->_str2) {
            $this->_error(self::NOT_EQUALS);
        }

        if (count($this->_messages)) {
            return false;
        } else {
            return true;
        }
    }
}
