<?php
/**
 * 画像サムネイル作成モデル
 * 
 * 対応はZend_Db_Selectのみ。
 * Zend_Db_Table　とかは考えない。　モデルだからその程度には具体的で良い。
 * あと、数字の定数化とかもしない。　面倒だから。
 */
class Media_Image
{
	/**
	 * セレクトオブジェクト
	 * @var Zend_Db_Select
	 */
	protected $_select;
	
	/**
	 * 画像オブジェクト
	 * @var Budori_Image
	 */
	protected $_image = null;
	
	
	/**
	 * データを格納しているカラム名
	 * @var string
	 */
	protected $_dataColmn = 'data';
	
	
	const MODE_SHAPE	= 's';
	
	const MODE_FIX		= 'f';
	
	const MODE_CAMPUS	= 'c';
	
	
	protected $_procs = array(
		self::MODE_SHAPE	=> 'shapeImage',
		self::MODE_FIX		=> 'fixImage',
		self::MODE_CAMPUS	=> 'campusImage',
	);
	
	
	/**
	 * Enter description here...
	 * @param Zend_Db_Select $select
	 */
	public function __construct(Zend_Db_Select $select=null)
	{
		$this->_select = $select;
	}
	
	/**
	 * Enter description here...
	 * @return Zend_Db_Select
	 */
	public function getSelect()
	{
		return $this->_select;
	}
	
	/**
	 * Enter description here...
	 * @param Zend_Db_Select $select
	 */
	public function setSelect(Zend_Db_Select $select)
	{
		$this->_select = $select;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $mode
	 * @return Budori_Image
	 */
	public function createImage($mode=null)
	{
		$select = $this->getSelect();
		
		if( !($select instanceof Zend_Db_Select )){
			throw new Budori_Exception('select object not setup');
		}
		
		$row = $select->getAdapter()->fetchAll($select);
		
		if( count($row) !== 1){
			throw new Budori_Exception('not found record');
		}
		
		$data =  Media::decodeData( $row[0]->{$this->_dataColmn} );
		
		$this->_image = new Budori_Image($data);
		
		if(!is_null($mode)){
			if(array_key_exists($mode,$this->_procs)){
				$method = $this->_procs[$mode];
				
				if( !method_exists($this,$method) ){
					throw new Budori_Exception("method not found '$method'");
				}
				call_user_func(array($this,$method));
			}
		}
		
		return $this->_image;
	}
	
	/**
	 * 変換1
	 */
	protected function shapeImage()
	{
		if(is_null($this->_image)){
			throw new Budori_Exception('image object is null');
		}
		$this->_image->shape(0.5,0.5);
	}
	
	/**
	 * 変換2
	 */
	protected function fixImage()
	{
		if(is_null($this->_image)){
			throw new Budori_Exception('image object is null');
		}
		$this->_image->fit(100,100);
	}
	
	/**
	 * 変換3
	 */
	protected function campusImage()
	{
		if(is_null($this->_image)){
			throw new Budori_Exception('image object is null');
		}
		$this->_image->fit(100,100)
				->ground(100,100,array(0xF0,0xFF,0xFF));
	}
}
