<?php
require_once 'Zend/Validate/Abstract.php';

/**
 * URIかどうかのチェック
 */
class Budori_Validate_Uri extends Zend_Validate_Abstract
{

    const WRONG_URI		= 'wrongUri';

    protected $_messageTemplates = array(
        self::WRONG_URI   => "'%value%' is wrong uri",
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value only contains digit characters
     *
     * @param  string  $value
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;

        $this->_setValue($valueString);

        if ('' === $valueString) {
            return true;
        }

        require_once 'Zend/Uri.php';

        $valid = Zend_Uri::check($value);
        if (!$valid) {
            $this->_error(self::WRONG_URI);

            return false;
        }

        return true;
    }
}
