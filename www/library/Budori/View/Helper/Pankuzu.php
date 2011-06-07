<?php
require_once 'Zend/View/Helper/HeadTitle.php';

/**
 * パンくずリストヘルパー
 * Zend_View_Helper_HeadTitleと基本的に同じ。
 * 最後の項目にはリンクをつけない。
 * ※　1.8 で Zend_Navigationがあるので不要になる
 */
class Budori_View_Helper_Pankuzu extends Zend_View_Helper_HeadTitle 
{
    /**
     * Registry key for placeholder
     * @var string
     */
    protected $_regKey = __CLASS__;
    
    
	/**
     * Flag wheter to automatically escape output, must also be
     * enforced in the child class if __toString/toString is overriden
     * @var book
     */
    protected $_autoEscape = false;
    
	/**
     * Retrieve placeholder for title element and optionally set state
     *
     * @param  string $title
     * @param  string $setType
     * @param  string $separator
     * @return Zend_View_Helper_HeadTitle
     */
    public function pankuzu($title = null, $setType = Zend_View_Helper_Placeholder_Container_Abstract::APPEND)
    {
        return parent::headTitle( $title, $setType );
    }

    
    /**
     * Turn helper into string
     *
     * @param  string|null $indent
     * @param  string|null $locale
     * @return string
     */
    public function toString($indent = "\t", $locale = null)
    {
        $indent = (null !== $indent)
                ? $this->getWhitespace($indent)
                : $this->getIndent();

        $items = array();
		
        $_cnt = $this->getContainer()->count();
        $ii = 1;
        
		foreach ($this as $item) {
			
			if( ( $item[1] != null ) && $ii != $_cnt ){
				$items[] = "<a href=\"$item[1]\">$item[0]</a>";
			}else{
				$items[]  = $item[0];
			}
			$ii++;
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
    
    
    /*
     * 以下マジックメソッドに対する補完用
     */
    
    
    
    public function set( $string, $target = null )
    {
    	return $this->getContainer()->set(array($string,$target));
    }
    
    public function prepend($string,$target=null)
    {
       	return $this->getContainer()->prepend(array($string,$target));
    }
    
    public function append($string,$target=null)
    {
    	return $this->getContainer()->append(array($string,$target));
    }
}
