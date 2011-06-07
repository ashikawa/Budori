<?php
require_once 'Zend/Validate/Abstract.php';


/**
 * Enter description here...
 */
class Budori_Validate_Selected extends Zend_Validate_Abstract
{
	
    const NOT_SELECTED = 'notSelected';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_SELECTED => "Value is must be Selected"
    );
	
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is not an empty value.
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue((string) $value);
		
		if ( empty($value)) {
            $this->_error();
            return false;
        }
		
        return true;
    }

}
