<?php
class Neri_Service_Amazon
{

    public static function getKey()
    {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();

        return $config['keys']['amazon'];
    }
}
