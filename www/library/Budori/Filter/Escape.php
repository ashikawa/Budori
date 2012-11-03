<?php
require_once 'Zend/Filter/HtmlEntities.php';

class Budori_Filter_Escape extends Zend_Filter_HtmlEntities
{
    public static $defaultQuoteStyle = ENT_QUOTES;

    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (!is_array($options)) {
            $options = func_get_args();
            $temp['quotestyle'] = array_shift($options);
            if (!empty($options)) {
                $temp['charset'] = array_shift($options);
            }
            $options = $temp;
        }

        if (!isset($options['quotestyle'])) {
            $options['quotestyle'] = self::$defaultQuoteStyle;
        }

        parent::__construct( $options );
    }
}
