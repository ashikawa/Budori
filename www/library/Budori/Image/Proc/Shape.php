<?php
require_once 'Budori/Image/Proc/Abstract.php';

/**
 * 画像を伸縮する
 * (元画像サイズ × 引数)
 * 引数を二つ設定した場合は width, heigth 倍
 */
class Budori_Image_Proc_Shape extends Budori_Image_Proc_Abstract 
{
	public function shape($px, $py=null)
	{
		if(is_null($py)){
			$py = $px;
		}
		
		$imageOrg	= $this->getResource();
		
		$imageNew	= new Budori_Image_Resource();
		
		$sw = $imageOrg->getWidth();
		$sh = $imageOrg->getHeight();
		
		$dw = $sw * $px;
		$dh = $sh * $py;
		
		$imageNew->create($dw,$dh);
		
		$imageNew->copyResized($imageOrg,0,0,0,0,$dw,$dh,$sw,$sh);
		
		$imageOrg->destroy();
		
		return $imageNew;
	}
}
