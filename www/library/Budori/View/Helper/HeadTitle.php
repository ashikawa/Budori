<?php
require_once 'Zend/View/Helper/HeadTitle.php';


class Budori_View_Helper_HeadTitle extends Zend_View_Helper_HeadTitle 
{
    protected $_regKey = __CLASS__;

    /**
     * Turn helper into string
     *
     * @param  string|null $indent
     * @param  string|null $locale
     * @return string
     */
    public function toString($indent = null, $locale = null)
    {
        $indent = (null !== $indent)
                ? $this->getWhitespace($indent)
                : $this->getIndent();

        $items = array();

        if($this->_translate && $translator = $this->getTranslator()) {
            foreach ($this as $item) {
                $items[] = $translator->translate($item, $locale);
            }
        } else {
            foreach ($this as $item) {
                $items[] = $item;
            }
        }

        $separator = $this->getSeparator();
        $output = '';
        if(($prefix = $this->getPrefix())) {
            $output  .= $prefix;
        }
        $output .= implode($separator, $items);
        if(($postfix = $this->getPostfix())) {
            $output .= $postfix;
        }

        $output = ($this->_autoEscape) ? $this->_escape($output) : $output;

        return $indent . $output;
    }
}
