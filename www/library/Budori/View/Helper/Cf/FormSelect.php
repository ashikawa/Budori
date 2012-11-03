<?php
require_once 'Zend/View/Helper/FormElement.php';

class Budori_View_Helper_Cf_FormSelect extends Zend_View_Helper_FormElement
{
    public function Cf_formSelect($name, $value = null, $attribs = null, $options = null)
    {
        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, id, value, attribs, options, listsep, disable

        if ($value === null) {
            $xhtml	= "&nbsp;";
        } else {

            if (is_array($value)) {

                $xhtml = "";

                foreach ($value as $_v) {
                    $xhtml .= $this->view->escape($options[$_v]) . PHP_EOL;
                }

            } else {
                $xhtml = $this->view->escape($options[$value]);
            }
        }

        return nl2br($xhtml);
    }
}
