<?php
/**
 * Youtube検索クエリ用のモデル
 */
class Youtube
{
	
	const APPLICATION_ID	= __CLASS__;
	
	
	/**
	 * Youtubeオブジェクト
	 * @var Zend_Gdata_YouTube
	 */
	protected $_youtube;
	
	/**
	 * 取得位置
	 * @var integer
	 */
	protected $_startIndex = 0;
	
	
	/**
	 * 取得する結果数
	 * @var integer
	 */
	protected $_maxResults = 5;
	
	
	/**
	 * コンストラクタ
	 * @param Zend_Http_Client $client
	 * @uses Zend_Http_Client
	 * @uses Zend_Gdata_YouTube
	 */
	public function __construct(Zend_Http_Client $client = null)
	{
		
		if(is_null($client)){
			$client = new Zend_Http_Client();
		}
		
		$clientId	= Neri_Service_Youtube::getClientKey();
		$devKey		= Neri_Service_Youtube::getDeveloperKey();
		
		$youtube = new Zend_Gdata_YouTube( $client, self::APPLICATION_ID, $clientId, $devKey);
		
		$this->_youtube = $youtube;
	}
	
	/**
	 * Youtubeオブジェクトの取得
	 * @return Zend_Gdata_YouTube
	 */
	public function getYoutube()
	{
		return $this->_youtube;
	}
	
	/**
	 * Youtubeオブジェクトの設定
	 * @param Zend_Gdata_YouTube $youtube
	 * @return GetYoutubeModel
	 */
	public function setYoutube( Zend_Gdata_YouTube $youtube )
	{
		$this->_youtube = $youtube;
		return $this;
	}
	
	/**
	 * スタートインデックスの設定
	 * @param integer $index
	 * @return GetYoutubeModel
	 */
	public function setStartIndex($index)
	{
		$this->_startIndex = $index;
		return $this;
	}
	
	/**
	 * 取得結果数の設定
	 * @param integer $results
	 * @return GetYoutubeModel
	 */
	public function setMaxResults($results)
	{
		$this->_maxResults = $results;
		return $this;
	}
	
	
	/**
	 * 検索クエリの実行
	 *
	 * @param string $keyword
     * @return Zend_Gdata_YouTube_VideoFeed The feed of videos found at the
     *         specified URL.
     * @uses Zend_Gdata_YouTube::getVideoFeed()
     */
	public function serach($keyword)
	{
		
		$query = $this->_youtube->newVideoQuery();
		
		$query->videoQuery	= $keyword;
		$query->startIndex	= $this->_startIndex;
		$query->maxResults	= $this->_maxResults;
		
		$query->orderBy		= 'viewCount';
		
		return $this->_youtube->getVideoFeed($query);
	}
	
}
