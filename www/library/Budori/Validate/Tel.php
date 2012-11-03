<?php
require_once 'Zend/Validate/Abstract.php';

/**
 * 電話番号かどうかのチェック
 * 半角数字とハイフンのみ 9～15文字
 */
class Budori_Validate_Tel extends Zend_Validate_Abstract
{

    protected $_min		= 9;
    protected $_max		= 15;
    protected $_convert_flg = true;
    protected $_pattern= '/^[\d-]*$/';

    const BAD_CHAR		= 'badCharacters';
    const TOO_SHORT		= 'stringLengthTooShort';
    const TOO_LONG		= 'stringLengthTooLong';

    protected $_messageTemplates = array(
        self::BAD_CHAR	=> "bad characters in '%value%'",
        self::TOO_SHORT => "'%value%' is less than %min% characters long",
        self::TOO_LONG  => "'%value%' is greater than %max% characters long",
    );

    protected $_messageVariables = array(
        'min' => '_min',
        'max' => '_max',
        'pattern' => '_pattern'
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

        $length = mb_strlen($valueString,'UTF-8');

        if( $length == 0 ) return true;

        if ($length < $this->_min) {
            $this->_error(self::TOO_SHORT);

            return false;
        }
        if (null !== $this->_max && $this->_max < $length) {
            $this->_error(self::TOO_LONG);

            return false;
        }

        if ($this->_convert_flg) {
            $status = @preg_match($this->_pattern, mb_convert_kana($valueString,'a'));
        } else {
            $status = @preg_match($this->_pattern, $valueString);
        }

        if (false === $status) {
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception("Internal error matching pattern '$this->_pattern' against value '$valueString'");
        }
        if (!$status) {
            $this->_error(self::BAD_CHAR);

            return false;
        }

        return true;
    }
}
