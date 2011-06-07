<?php

/**
 * 画像リソースオブジェクト。
 * このクラス resource 型のみ扱い、は画像タイプに依存しない。
 * 必要な php image.. 関数はここで再定義する
 * 
 * @todo エラー処理その他
 * @todo 画像が大きすぎる、小さすぎる時の制御。
 * @todo Colorオブジェクト必要？ その他諸々。
 */
class Budori_Image_Resource
{
	/**
	 * 画像データ(php resource型)
	 * @var resource
	 */
	protected $_data = null;
	
	/**
	 * コンストラクタ
	 * @param resource $data
	 */
	public function __construct($data = null)
	{	
		if(!is_null($data)){
			$this->setData($data);
		}
	}
	
	/**
	 * リソースデータの設定
	 * @param resource $resource
	 */
	public function setData($data)
	{
		if(!is_resource($data)){
			require_once 'Budori/Image/Exception.php';
			throw new Budori_Image_Exception('wrong data type');
		}
		
		$this->_data = $data;
	}
	
	/**
	 * リソースデータの取得
	 * @todo ここの例外処理に違和感
	 * @return resource
	 */
	public function getData()
	{
		if(!is_resource($this->_data)){
			require_once 'Budori/Image/Exception.php';
			throw new Budori_Image_Exception('wrong data type');
		}
		return $this->_data;
	}
	
	/**
	 * 画像の横幅を取得
	 * @return integer
	 */
	public function getWidth()
	{
		return imagesx($this->getData());
	}
	
	/**
	 * 画像の高さを取得
	 * @return integer
	 */
	public function getHeight()
	{
		return imagesy($this->getData());
	}
	
	/**
	 * 画像リソースの削除
	 * @return Budori_Image
	 */
	public function destroy()
	{
		if(!is_null($this->_data) && is_resource($this->_data)){
			imagedestroy($this->_data);
		}
		return $this;
	}
	
	/**
	 * サイズを指定し、新規画像の作成
	 * @param integer $width
	 * @param integer $height
	 * @return Budori_Image
	 */
	public function create($width, $height)
	{
		$this->destroy();
		
		$data = imagecreatetruecolor($width, $height);
		$this->setData($data);
		return $this;
	}
	
	public function getColorAllocate($red, $green, $blue)
	{
		$data = $this->getData();
		return imagecolorallocate($data, $red, $green, $blue);
	}
	
	/**
	 * 画像を拡大、縮小してコピー
	 * 
	 * @param Budori_Image $image
	 * @param integer $dx
	 * @param integer $dy
	 * @param integer $sx
	 * @param integer $sy
	 * @param integer $dw
	 * @param integer $dh
	 * @param integer $sw
	 * @param integer $sh
	 * @return Budori_Image
	 */
	public function copyResized( Budori_Image_Resource $image, $dx, $dy, $sx, $sy, $dw, $dh, $sw, $sh)
	{
		$new = $this->getData();
		
//		imagecopyresized($new,$image->getData(),$dx,$dy,$sx,$sy,$dw,$dh,$sw,$sh );
		imagecopyresampled($new,$image->getData(),$dx,$dy,$sx,$sy,$dw,$dh,$sw,$sh );
		$this->setData($new);
		return $this;
	}
	
	/**
	 * Enter description here...
	 * @param array $color
	 * @return Budori_Image
	 */
	public function fill($color)
	{
		imagefill($this->getData(),0,0,$color);
		return $this;
	}
	
	/**
	 * Enter description here...
	 * @param Budori_Image $image
	 * @param integer $dx
	 * @param integer $dy
	 * @param integer $sx
	 * @param integer $sy
	 * @param integer $sw
	 * @param integer $sh
	 * @return Budori_image
	 */
	public function copy( Budori_Image_Resource $image,$dx,$dy,$sx,$sy,$sw,$sh )
	{
		$new = $this->getData();
		
		imagecopy($new, $image->getData(),$dx,$dy,$sx,$sy,$sw,$sh);
		
		$this->setData($new);
		return $this;
	}
	
	public function imageString($text, $size, $angle, $x, $y, $color, $font )
	{
		$resource = $this->getData();
		
		imagettftext ($resource, $size, $angle, $x, $y, $color, $font, $text);
		
		return $this;
	}
	
}
