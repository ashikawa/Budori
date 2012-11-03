<?php
require_once 'Zend/View/Helper/Abstract.php';

class Budori_View_Helper_Autoescape extends Zend_View_Helper_Abstract
{
    const MODIFIERS_KEY = 'escape';

    public function Autoescape($flag)
    {
        $engine = $this->view->getEngine();

        if ($engine instanceof Smarty) {

            $key = self::MODIFIERS_KEY;

            if ($flag) {
                $engine->default_modifiers[$key];
            } else {
                unset($engine->default_modifiers[$key]);
            }
        }
    }
}
