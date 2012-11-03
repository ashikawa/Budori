<?php
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @see http://faces.jp/2008/08/flash_1.html
 *
 */
class Budori_View_Helper_Flash extends Zend_View_Helper_Abstract
{

    public function flash($file, $item, $keepLayouts=false)
    {

        if (!$keepLayouts) {
            require_once 'Zend/Layout.php';
            $layout = Zend_Layout::getMvcInstance();
            if ($layout instanceof Zend_Layout) {
                $layout->disableLayout();
            }
        }

        require_once 'Zend/Controller/Front.php';
        $response = Zend_Controller_Front::getInstance()->getResponse();
        $response->setHeader('Content-Type','application/x-shockwave-flash');

        return $this->build($file, $item);
    }

    public function build($file, $item)
    {
        $tags	= $this->build_tags($item);
        $src	= file_get_contents($file);

        if ($src === false) {
            require_once 'Budori/Exception.php';
            throw new Budori_Exception("cannot read file $file");
        }

        $i	= (ord($src[8])>>1)+5;
        $length	= ceil((((8-($i&7))&7)+$i)/8)+17;
        $head	= substr($src,0,$length);

        return(
            substr($head,0,4).
            pack("V",strlen($src)+strlen($tags)).
            substr($head,8).
            $tags.
            substr($src,$length)
        );
    }

    protected function build_tags($item)
    {
        $tags = array();

        foreach ($item as $k => $v) {
            $v = mb_convert_encoding( $v, 'SJIS', 'auto' );

            array_push( $tags, sprintf(
                "\x96%s\x00%s\x00\x96%s\x00%s\x00\x1d",
                pack("v",strlen($k)+2),	$k,
                pack("v",strlen($v)+2),	$v
            ));
        }
        $s = implode('',$tags);

        return(sprintf(
            "\x3f\x03%s%s\x00",
            pack("V",strlen($s)+1),
            $s
        ));
    }
}
