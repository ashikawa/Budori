<?php

/**
 * 画像編集処理クラス
 */
abstract class Budori_Image_Proc_Abstract
{
	/**
	 * リソースオブジェクト
	 * @var Budori_Image_Resource
	 */
	protected $_resource;
	
	/**
	 * コンストラクタ
	 * @param Budori_Image_Resource $image
	 */
	public function __construct( Budori_Image_Resource $resource=null )
	{
		$this->setResource($resource);
	}
	
	/**
	 * リソースオブジェクトの取得
	 * @return Budori_Image_Resource
	 */
	public function getResource()
	{
		return $this->_resource;
	}
	
	/**
	 * リソースオブジェクトの設置
	 * @param Budori_Image_Resource $resource
	 */
	public function setResource(Budori_Image_Resource $resource)
	{
		$this->_resource = $resource;
	}
}
