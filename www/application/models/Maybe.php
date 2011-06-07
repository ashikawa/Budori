<?php
/**
 * もしかして○○検索。
 */
class Maybe
{
	/**
	 * 検索するリスト
	 * 優先順位が高い順に並んでいる必要がある
	 * @var array
	 */
	protected $_list = array();
	
	public function __construct()
	{}
	
	
	/**
	 * 検索の実行
	 * @param string $word
	 * @return Maybe_Result
	 */
	public function search( $word)
	{
		$list = $this->getList();
		return $this->_search($word,$list);
	}
	
	/**
	 * 検索エンジン
	 * @todo 要、精度の確認と向上。
	 * @todo 同じ距離の物が複数あった場合は?
	 * 
	 * @param string $word
	 * @param array $list
	 * @return Maybe_Result
	 */
	protected function _search( $word, $list )
	{
		$shortest	= -1;
		
		foreach ($list as $_v){
			
			$lev = levenshtein($word, $_v);
			
			if ($lev === 0) {
				$value		= $_v;
				$shortest	= 0;
				break;
			}
			
			//優先順位高い順に並べるなら '<' 同じ距離の物は上書きしない
//			if ($lev <= $shortest || $shortest < 0) {
			if ($lev < $shortest || $shortest < 0) {
				$value		= $_v;
				$shortest	= $lev;
			}
			
		}
		
		$result = new Maybe_Result($shortest, $value);
		return $result;
	}
	
	public function setList($list)
	{
		$this->_list = $list;
	}
	
	/**
	 * @return array
	 */
	public function getList()
	{
		return $this->_list;
	}
}
