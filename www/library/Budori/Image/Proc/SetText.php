<?php
require_once 'Budori/Image/Proc/Abstract.php';

class Budori_Image_Proc_SetText extends Budori_Image_Proc_Abstract
{
    public function setText($text,$size,$angle,$x,$y,$color,$font)
    {
        $colorAllocate	= $this->getResource()->getColorAllocate($color[0],$color[1],$color[2]);

        $this->getResource()->imageString($text, $size, $angle, $x, $y, $colorAllocate, $font );

        return $this->getResource();
    }
}
