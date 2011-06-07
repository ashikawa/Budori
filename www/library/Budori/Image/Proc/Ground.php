<?php
require_once 'Budori/Image/Proc/Abstract.php';

/**
 * 伸縮せずに、画像の大きさを広げる。
 */
class Budori_Image_Proc_Ground extends Budori_Image_Proc_Abstract 
{
	
	public function ground($width, $height, $color)
	{
		$imageOrg	= $this->getResource();
		
		$imageNew	= new Budori_Image_Resource();
		$imageNew->create($width, $height);
		
		$color = imagecolorallocate($imageNew->getData(),$color[0],$color[1],$color[2]);
		
		$imageNew->fill($color);
		
		$sw	= $imageOrg->getWidth();
		$sh	= $imageOrg->getHeight();
		
		$dw	= $width;
		$dh	= $height;
		
		$x	= ($dw - $sw) / 2;
		$y	= ($dh - $sh) / 2;
		
		$imageNew->copy($imageOrg,$x,$y,0,0,$sw,$sh);
		
		$imageOrg->destroy();
		return $imageNew;
	}
}
