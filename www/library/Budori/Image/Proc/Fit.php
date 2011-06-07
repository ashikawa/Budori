<?php
require_once 'Budori/Image/Proc/Abstract.php';

/**
 * 画像を特定の範囲にフィットするように伸縮する
 */
class Budori_Image_Proc_Fit extends Budori_Image_Proc_Abstract 
{
	public function fit($maxW, $maxH)
	{
		$imageOrg	= $this->getResource();
		
		$imageNew	= new Budori_Image_Resource();
		
		$sw = $imageOrg->getWidth();
		$sh = $imageOrg->getHeight();
		
		$p = max($sw / $maxW, $sh / $maxH);
		
		$dw = $sw / $p;
		$dh = $sh / $p;
		
		$imageNew->create($dw,$dh);
		
		$imageNew->copyResized($imageOrg,0,0,0,0,$dw,$dh,$sw,$sh);
		
		$imageOrg->destroy();
		
		return $imageNew;
	}
}
