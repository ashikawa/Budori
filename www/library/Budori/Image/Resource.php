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
	 * 画像読み込み用関数(callback)
	 * @var array
	 */
	protected $_input	= array(
		IMAGETYPE_GIF	=> 'imagecreatefromgif',
		IMAGETYPE_JPEG	=> 'imagecreatefromjpeg',
		IMAGETYPE_PNG	=> 'imagecreatefrompng',
	);
	
	/**
	 * 画像出力用関数(callback)
	 * @var array
	 */
	protected $_output	= array(
		IMAGETYPE_GIF	=> 'imagegif',
		IMAGETYPE_JPEG	=> 'imagejpeg',
		IMAGETYPE_PNG	=> 'imagepng',
	);
	
	/**
	 * 許可する画像タイプ
	 * @var unknown_type
	 */
	protected $_arrowType = array(
		IMAGETYPE_GIF,
		IMAGETYPE_JPEG,
		IMAGETYPE_PNG,
	);
	
	/**
	 * 画像タイプ
	 * @var integer
	 */
	protected $_type;
	
	/**
	 * 出力画像品質
	 * @var integer
	 */
	protected $_quality = 100;
	
	/**
	 * 画像データ(php resource型)
	 * @var resource
	 */
	protected $_data = null;
	
	/**
	 * コンストラクタ
	 * 入力値から画像タイプを判別して、リソースオブジェクトの初期化
	 * @param mix $data | string or file path
	 */
	public function __construct($data=null)
	{
		if(!is_null($data)){
			
			require_once 'Budori/File/Mime.php';
			$mime = new Budori_File_Mime();
			
			if(is_file($data)){
				
				$mime = $mime->file($data);
				
				$type = $this->_getImageTypeFromMime($mime);
				
				if( !in_array($type,$this->_arrowType) ){
					throw new Budori_Image_Exception("wrong data type");
				}
				
				$this->_loadResourceFile($data, $type);
				
			}else if(is_string($data)){
				$mime = $mime->buffer($data);
				
				$type = $this->_getImageTypeFromMime($mime);
				
				if( !in_array($type,$this->_arrowType) ){
					throw new Budori_Image_Exception("wrong data type");
				}
				
				$this->_loadResourceString($data);
			}
			
			$this->_type		= $type;
		}
	}
	
	
	/**
	 * mimeタイプから、画像タイプの取得
	 * @param string $mime
	 * @return integer
	 */
	protected function _getImageTypeFromMime($mime)
	{
		switch ($mime){
			case 'image/gif':
				return IMAGETYPE_GIF;
				break;
			case 'image/jpeg':
				return IMAGETYPE_JPEG;
				break;
			case 'image/png':
				return IMAGETYPE_PNG;
				break;
			default:
				break;
		}
		return null;
	}
	
	/**
	 * ファイルからリソースオブジェクトの設定
	 * @param string $file
	 * @param sring $type
	 * @return Budori_Image_Resource
	 */
	protected function _loadResourceFile($file, $type)
	{
		$this->_data = call_user_func_array($this->_input[$type], array($file));
	}
	
	/**
	 * テキスト情報からリソースオブジェクトの設定
	 * @param string $data
	 * @return Budori_Image_Resource
	 */
	protected function _loadResourceString($data)
	{
		$this->_data = imagecreatefromstring($data);
	}
	
	
	
	/**
	 * 画像タイプを取得
	 * @return integer
	 */
	public function getType()
	{
		return $this->_type;
	}
	
	/**
	 * 画像タイプを設定
	 * @param integer $type
	 */
	public function setType($type)
	{
		$this->_type = $type;
	}
	
	/**
	 * 画像の品質を取得
	 * @return integer
	 */
	public function getQuality()
	{
		return $this->_quality;
	}
	
	/**
	 * 出力する画像の品質を設定(0～100 jpg,png のみ)
	 * @param integer $quality
	 */
	public function setQuality($quality)
	{
		$this->_quality = $quality;
	}
	
	/**
	 * Mimeタイプの取得
	 * @return string
	 */
	public function getMime()
	{
		return image_type_to_mime_type($this->getType());
	}
	
	
	/**
	 * 画像の保存
	 * $stream 指定時には空文字が変える
	 * 
	 * 
	 * @param string $stream
	 * @return string
	 */
	public function saveImage( $stream = null )
	{
		$type	= $this->getType();
		
		
		/**
		 * オプションの切り分け
		 * imagejpg の quality オプションは 0 ～ 100
		 * imagepng の quality オプションは 0 ～ 9
		 * pngのqualityは無視しておく
		 */
		switch ($type){
			case IMAGETYPE_GIF:
			case IMAGETYPE_PNG:
				$arg = array( $this->_data, $stream );
				break;
			case IMAGETYPE_JPEG:
				$arg = array( $this->_data, $stream, $this->getQuality() );
				break;
			default:
				require_once 'Budori/Image/Exception.php';
				throw new Budori_Image_Exception("un supported file type $type");
				break;
		}
		
		ob_start();
		call_user_func_array($this->_output[$type], $arg);
		$data = ob_get_contents();
		ob_clean();
		
		return $data;
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
