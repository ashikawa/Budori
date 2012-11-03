<?php
/**
 * AmazonAPIを利用するためのモデル
 * @see http://docs.amazonwebservices.com/AWSEcommerceService/4-0/
 */
class Amazon
{
    const APPAREL				= 'Apparel';
    const BABY					= 'Baby';
    const BEAUTY				= 'Beauty';
    const BLENDED				= 'Blended';
    const BOOKS					= 'Books';
    const CLASSICAL				= 'Classical';
    const DVD					= 'DVD';
    const ELECTRONICS			= 'Electronics';
    const FOREIGN_BOOKS			= 'ForeignBooks';
    const GROCERY				= 'Grocery';
    const HEALTH_PERSONAL_CARE	= 'HealthPersonalCare';
    const HOBBIES				= 'Hobbies';
    const JEWELRY				= 'Jewelry';
    const KITCHEN				= 'Kitchen';
    const MARKETPLACE			= 'Marketplace';
    const MUSIC					= 'Music';
    const MUSIC_TRACKS			= 'MusicTracks';
    const SOFTWARE				= 'Software';
    const SPORTING_GOODS		= 'SportingGoods';
    const TOYS					= 'Toys';
    const VHS					= 'VHS';
    const VIDEO					= 'Video';
    const VIDEO_GAMES			= 'VideoGames';
    const WATCHES				= 'Watches';

    const ACCESSORIES			= 'Accessories';
    const BROWSE_NODE_INFO		= 'BrowseNodeInfo';
    const BROWSE_NODES			= 'BrowseNodes';
    const CART					= 'Cart';
    const CART_SIMILARITIES		= 'CartSimilarities';
    const CUSTOMER_FULL			= 'CustomerFull';
    const CUSTOMER_INFO			= 'CustomerInfo';
    const CUSTOMER_LISTS		= 'CustomerLists';
    const CUSTOMER_REVIEWS		= 'CustomerReviews';
    const EDITORIAL_REVIEW		= 'EditorialReview';
    const HELP					= 'Help';
    const IMAGES				= 'Images';
    const ITEM_ATTRIBUTES		= 'ItemAttributes';
    const ITEM_IDS				= 'ItemIds';
    const LARGE					= 'Large';
    const LIST_FULL				= 'ListFull';
    const LIST_INFO				= 'ListInfo';
    const LIST_NAMES			= 'ListItems';
    const LISTMANIA_LISTS		= 'ListmaniaLists';
    const LIST_MINIMUM			= 'ListMinimum';
    const MEDIUM				= 'Medium';
    const OFFER_FULL			= 'OfferFull';
    const OFFERS				= 'Offers';
    const OFFER_SUMMARY			= 'OfferSummary';
    const REQUEST				= 'Request';
    const REVIEWS				= 'Reviews';
    const SALES_RANK			= 'SalesRank';
    const SELLER				= 'Seller';
    const SELLER_LISTING		= 'SellerListing';
    const SIMILARITIES			= 'Similarities';
    const SMALL					= 'Small';
    const TRACKS				= 'Tracks';
    const TRANSACATION_DETAILS	= 'TransactionDetails';
    const VARIATIONS_MINIMUM	= 'VariationMinimum';
    const VARIATIONS			= 'Variations';
    const VARIATION_SUMMARY		= 'VariationSummary';

    /**
     * Enter description here...
     * @var Zend_Service_Amazon_Query
     */
    protected $_query;

    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    protected $_responce = array();

    /**
     * コンストラクタ
     * @uses Zend_Service_Amazon_Query
     */
    public function __construct()
    {
        $key  = Neri_Service_Amazon::getKey();

        $this->setQuery( new Zend_Service_Amazon_Query( $key ,'JP') );
    }

    /**
     * クエリオブジェクトの設定
     * @param  Zend_Service_Amazon_Query $query
     * @return GetAmazonModel
     */
    public function setQuery(Zend_Service_Amazon_Query $query)
    {
        $this->_query = $query;

        return $this;
    }

    /**
     * クエリオブジェクトの取得
     * @return Zend_Service_Amazon_Query
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * カテゴリの設定
     * @param  string         $category
     * @return GetAmazonModel
     */
    public function setCategory( $category )
    {
        $this->_query->category($category);

        return $this;
    }

    /**
     * キーワードの設定
     * @param  string         $keywords
     * @return GetAmazonModel
     */
    public function setKeywords( $keywords )
    {
        $this->_query->Keywords($keywords);

        return $this;
    }

    /**
     * レスポンスの設定
     * @param  string         $responce
     * @return GetAmazonModel
     */
    public function setResponse( $responce )
    {
        $this->_responce[] = $responce;

        return $this;
    }

    /**
     * 検索の実行
     * @return Zend_Service_Amazon_Item|Zend_Service_Amazon_ResultSet
     */
    public function search()
    {
        return $this->_query->ResponseGroup(implode(',',$this->_responce))
                        ->search();
    }

    public function clearResponse()
    {
        $this->_responce = array();
    }

    public function searchClassic($keyword)
    {
        $this->clearResponse();

        $this->setCategory(Amazon::CLASSICAL)
                ->setCategory(Amazon::DVD)
                ->setCategory(Amazon::MUSIC)
                ->setResponse(Amazon::SMALL)
                ->setResponse(Amazon::IMAGES)
                ->setResponse(Amazon::TRACKS);

        $this->setKeywords($keyword);

        return $this->search();
    }
}
