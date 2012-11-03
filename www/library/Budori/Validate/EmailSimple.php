<?php
require_once 'Zend/Validate/Abstract.php';

class Budori_Validate_EmailSimple extends Zend_Validate_Abstract
{
    const INVALID            = 'emailSimpleInvalid';

    const INVALID_FORMAT     = 'emailSimpleInvalidFormat';

    protected $_matchPattern = "/^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,4}$/";

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID            => "Invalid type given, value should be a string",
        self::INVALID_FORMAT     => "'%value%' is no valid email address in the basic format local-part@hostname",
        );

    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->_error(self::INVALID);

            return false;
        }

        $this->_setValue($value);

        if ( !preg_match($this->_matchPattern,$value) ) {
            $this->_error(self::INVALID_FORMAT);

            return false;
        }

        return true;
    }
}
