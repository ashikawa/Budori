<?php
require_once 'Zend/Validate/Abstract.php';

/**
 * @todo Zend_Localかなんか
 *
 */
class Budori_Validate_Zip extends Zend_Validate_Abstract
{

    protected $_country = 'jp';

    public function isValid()
    {
        switch ($this->_country) {
            case 'jp':
                $regex = '/^[1-9]{3}-[0-9]{4}$/i';
                break;
            case 'uk':
                $regex = '/\\A\\b[A-Z]{1,2}[0-9][A-Z0-9]? [0-9][ABD-HJLNP-UW-Z]{2}\\b\\z/i';
                break;
            case 'ca':
                $regex = '/\\A\\b[ABCEGHJKLMNPRSTVXY][0-9][A-Z] [0-9][A-Z][0-9]\\b\\z/i';
                break;
            case 'it':
            case 'de':
                $regex = '/^[0-9]{5}$/i';
                break;
            case 'be':
                $regex = '/^[1-9]{1}[0-9]{3}$/i';
                break;
            case 'us':
                $regex = '/\\A\\b[0-9]{5}(?:-[0-9]{4})?\\b\\z/i';
                break;
            case 'us':
                $regex = '/\\A\\b[0-9]{5}(?:-[0-9]{4})?\\b\\z/i';
                break;
            default:
                require_once 'Zend/Validate/Exception.php';
                throw new Zend_Validate_Exception();
                break;
        }
    }

}
