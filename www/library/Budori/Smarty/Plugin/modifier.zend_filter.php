<?php
require_once 'Zend/Filter.php';
require_once 'Zend/Filter/Interface.php';

/**
 * call Zend_Filter_Interface as Smarty modifier
 *
 * @param string $string
 * @param Zend_Filter_Interface $filter
 */
function smarty_modifier_zend_filter( $string, $filter )
{
    if ($filter instanceof Zend_Filter_Interface) {
        return $filter->filter($string);
    } else {
        return Zend_Filter::filterStatic($string, $filter);
    }
}
