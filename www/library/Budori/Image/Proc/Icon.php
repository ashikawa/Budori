<?php
require_once 'Budori/Image/Proc/Abstract.php';

/**
 * Fitの別バージョン。
 * 画像の内側に合わせる
 * @todo 動作テスト
 */
class Budori_Image_Proc_Icon extends Budori_Image_Proc_Abstract 
{
	public function icon($maxW, $maxH)
	{
//		$image->setResource($imageNew);
//		return $image;
		
		$imageOrg	= $this->getResource();
		
		$imageNew	= new Budori_Image_Resource();
		
		$sw = $imageOrg->getWidth();
		$sh = $imageOrg->getHeight();
		
		$p = max($sw / $maxW, $sh / $maxH);
		
		$dw = $sw / $p;
		$dh = $sh / $p;
		
		$sx	= ( $dw - $maxW ) / 2;
		$sy	= ( $dh - $maxH ) / 2;
		
		$imageNew->create($maxW,$maxH);
		
		$imageNew->copyResized($imageOrg,0,0,$sx,$sy,$dw,$dh,$sw,$sh);
		
		$imageOrg->destroy();
		
		return $imageNew;
	}
}
