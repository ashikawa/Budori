<?php
require_once 'Zend/Filter/Inflector.php';

class Budori_Filter_Nl2br extends Zend_Filter_Inflector
{
    public function filter( $value )
    {
        return nl2br($value);
    }
}
