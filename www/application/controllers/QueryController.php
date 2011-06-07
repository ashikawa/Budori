<?php
/**
 * 各方法でのクエリ発行
 * 
 * Maybe_Url に replace の簡単な例があります。
 * @todo これはクエリのテストだが、実際にはモデルに集める事。
 * ※とりあえず、コントローラにDBのカラム名が混ざらないように。
 * 
 * !!!!! 実装する時はちゃんとモデルにまとめる事 !!!!!
 */
class QueryController extends Neri_Controller_Action_Http 
{
	const LIMIT_SELECT = 3;
	
	const LIMIT_SEARCH = 10;
	
	public function preDispatch()
	{
		parent::preDispatch();
		
		$this->prependTitle('query');
		$this->appendPankuzu('query','/' . $this->getRequest()->getControllerName() );
	}
	
	public function indexAction()
	{}
	
	
	/**
	 * 一般的なクエリの例
	 */
	public function selectAction()
	{
		$db = Budori_Db::factory();
		
		$limit	= self::LIMIT_SELECT;
		$offset = 0;
		
		$select = new Neri_Db_Select_Member($db);
		
		$select->setDefault()
					->orderById(Zend_Db_Select::SQL_ASC)
					->limit($limit, $offset);
		
		$result = $db->fetchAll($select);
		
		$this->view->assign('result',$result);
	}
	
	
	/**
	 * 一般的な検索の例
	 * @see http://framework.zend.com/manual/ja/zend.paginator.usage.html
	 */
	public function searchAction()
	{
		$params = array_merge(
			array(
				'p'	=> 1,
			), $this->_getAllParams() );
		
		
		$limit	= self::LIMIT_SEARCH;
		$page	= intval($params['p']);
		$db		= Budori_Db::factory();
		
		/**
		 * ここは 普通の select
		 */
		$select = new Neri_Db_Select_Zip($db);
		
		$select->setPref('東京都')
				->orderByCode();
		
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		$paginator->setCurrentPageNumber($page)
					->setItemCountPerPage($limit);
		
		
		$this->view->assign('paginator',$paginator);
	}
	
	
	
	/**
	 * この辺いろいろ違和感。
	 * 特に join　周りの処理。
	 * 
	 * 結合済みのクラス( Neri_Db_Select_Member_Media )を作る？
	 *  -> クラスが級数的に増える & 階層が深くなりすぎる恐れあり。
	 * 
	 * 結合済みの物はモデルで実装。
	 * パラメータで条件を変更できるように設計。
	 */
	public function joinAction()
	{
		$db = Budori_Db::factory();
		
		//mediaテーブルのクエリ
		$media = new Neri_Db_Select_Media($db);
		
		$media->setColumns(array('k' => 'me.key','n'=>'me.name','e'=>'me.ext'))
				->setDefault();
		
		//memberテーブルのクエリ
		$member = new Neri_Db_Select_Member($db);
		$member->setDefault();
		
		//テーブルの結合
		$media->join( array('m' => $member), '"me"."owner" = "m"."key"', array('m.key') );
		
		$assign = array(
			'result'	=> $db->fetchAll($media),
		);
		
		$this->view->assign( $assign );
	}
	
	
	/**
	 * joinAction より多少マシだが、複雑なクエリに対応できない予感。
	 * 結合条件を結局個別に書いてあるのも面倒。
	 * 
	 * (でも、もしかしたらこれで十分なのかも)
	 */
	public function join2Action()
	{
		$owner = 'ashikawa';
		
		$db = Budori_Db::factory();
		
		$media = new Neri_Db_Select_Media($db);
		$media->setColumns( array( 'me.key', 'me.name', 'me.ext' ) )
				->join( array('m'=>'member'), '"me"."owner" = "m"."key"', array('m.key') )
				->setOwner($owner)
				->setDefault();
		
		$assign = array(
			'result'	=> $db->fetchAll($media),
		);
		
		$this->view->assign( $assign );
	}
	
	
	
	/**
	 * このくらいが妥当な線。
	 * select は Zend_Db_Select
	 * update,insert はZend_Db_Table
	 * 複雑な結合条件は **_SearchModelにまとめる方向で。
	 * Zend_Db_Select を継承していれば Paginater も使える
	 * 
	 * ※ Zend_Db_Select は Inner Join で結合するために、インデックス等に注意が必要。
	 */
	public function join3Action()
	{
		$owner = 'ashikawa';
		
		$db = Budori_Db::factory();
		
		$model = new Media_Search($db);
		
		$model->setOwner($owner)->setDefault();
		
		$assign = array(
			'result'	=> $db->fetchAll($model),
		);
		
		$this->view->assign( $assign );
	}
	
	/**
	 * テーブルゲートウェイパターン。
	 * @see Zend_Db_Table_Abstract::setDefaultMetadataCache()
	 */
	public function findAction()
	{
		$db = Budori_Db::factory();
		
		$table = new Neri_Db_Table_Member($db);
		
		$assign = array(
			'result'	=> $table->find('ashikawa')->current(),
		);
		
		$this->view->assign( $assign );
	}
	
	
	/**
	 * SELECT * FROM ... WHERE key IN (hoge,moge)
	 */
	public function inAction()
	{
		$db = Budori_Db::factory();
		
		$select = new Neri_Db_Select_Zip($db);
		
		$cities = array('新宿区','中野区');
		$select->in('z.city',$cities);
		
		
		$assign = array(
			'result'	=> $db->fetchAll($select),
		);
		
		$this->view->assign( $assign );
	}
	
	
	/**
	 * SELECT ..., count(*) FROM ... GROUP BY ...
	 */
	public function groupAction()
	{
		$db = Budori_Db::factory();
		
		$select = new Neri_Db_Select_Zip($db);
		$select->setColumns(array('z.city','count(*)'))
				->group('z.city');
		
		
		$assign = array(
			'result'	=> $db->fetchAll($select),
		);
		$this->view->assign( $assign );
	}
	
	/**
	 * 通常のupdate処理
	 */
	public function updateAction()
	{
		$db = Budori_Db::factory();
		
		$member = new Neri_Db_Table_Zip($db);
		
		$db->beginTransaction();
		
		try {
			
			$result = $member->update(array('pref' => '東京都'), $db->quoteInto(' code = ? ', '1000000' ) );
			
		}catch (Exception $e){
			
			$db->rollBack();
			throw $e;
		}
		
		$db->commit();
		
		$this->view->assign('result',$result);
	}
	
	
	
	/**
	 * find -> save
	 * saveした後、オブジェクトの再読み込みをしているっぽい
	 * クエリー吐きすぎて若干ウザい。
	 */
	public function tableupdateAction()
	{
		$db = Budori_Db::factory();
		
		$member = new Neri_Db_Table_Member($db);
		
		$db->beginTransaction();
		
		try {
			$row = $member->find('ashikawa')->current();
			
			if( is_null($row) ){
				//要件に合わせて Zend_Controller_Action_Exception('...',404) とか。
				throw new Exception();
			}
			
			$row->name = 'あしかわ';
			$result = $row->save();
			
		}catch (Exception $e){
			
			$db->rollBack();
			throw $e;
		}
		
		$db->commit();
		
		$assign = array(
			'result'	=> $result,
		);
		
		$this->view->assign($assign);
	}
	
	/**
	 * テーブルの情報を取得、キャッシュに保存
	 */
	public function infoAction()
	{
		$db = Budori_Db::factory();
		
		$member = new Neri_Db_Table_Member($db);
		
		$assign = array(
			'result' => $member->info(),
		);
		
		$this->view->assign($assign);
	}
	
	/**
	 * 全文検索の動作確認
	 */
	public function textsearchAction()
	{
		
		$params = array_merge(
			array(
				't'	=> null,
			),$this->_getAllParams());
		
		
		if(is_string($params['t'])){
			
			$db = Budori_Db::factory();
			
			$select = new Neri_Db_Select_Text($db);
			
			$select->textSearch( $params['t'] )
						->setColumns( array('name', 'data'));
			
			$this->view->assign('result',$db->fetchAll($select));
		}
	}
	
	/**
	 * 特殊なデータ型の扱い
	 * 
	 * !tips	副問い合わせの結果を配列型に直す例
	 * 	UPDATE t_array SET data = ARRAY(SELECT key FROM media ) WHERE key = 2;
	 *	副問い合わせの生成は　$select->assemble(); (or __toString())　みたいな感じで。
	 * 	Zend_Db_Expr とか使って頑張る。
	 */
	public function arrayAction()
	{
		$db = Budori_Db::factory();
		
		$select = new Neri_Db_Select_TArray($db);
		
		$assign = array(
			'result'	=> $db->fetchAll($select),
		);
		
		$this->view->assign($assign);
	}
	
	
	
	/**
	 * SQL出力結果をキャッシュに保存。
	 * 内容の変わらないテーブルに使えるかも.....
	 * 「新着情報」的な物とかも、ちょっと溜めていいと思う。
	 */
	public function cacheAction()
	{
		$cacheId = 'QueryController_cacheAction';
		
		$cache = Budori_Cache::factory('file');
		
		if( !$cache->test($cacheId) ){
			$db = Budori_Db::factory();
			
			$select = new Neri_Db_Select_Zip($db);
			$select->setCity('中野区')
								->limit(5,0);
			
			$result = $db->fetchAll($select);
			
			$cache->save($result, $chacheId);
		} else {
			$result = $cache->load($cacheId);
		}
		
		$assign = array(
			'result'	=> $result,
		);
		
		$this->view->assign($assign);
	}
}

