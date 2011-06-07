<?php
/**
 * 全文検索エンジン tesxt_search_ja 用クラス
 */
class TextSearchJa
{
	/**
	 * 文字列を to_tsquery (全文検索)　用に変換
	 * @param string $string
	 * @return string
	 */
	public static function getTsArray( $string )
	{
		//記号をエスケープ
		$string = preg_replace( '/[&]/', '\&', $string );
		$string = preg_replace( '/[\|]/', '\|', $string );
		$string = preg_replace( '/[!]/', '\!', $string );
		
		//シングルクオートを半角スペースに変換
		$string = preg_replace( '/\'/', ' ', $string );
		
		//単語に分解
		$strArray = preg_split('/[\s\|])/u', $string );
		
		$out = array();
		
		//一文字以下の要素を削除
		if( count($strArray) > 0 ){
			foreach ($strArray as $_key => $_value){
				if( strlen($_value) > 1 ) $out[$_key] = $_value;
			}
		}
		
		return  implode(" & ", $out);
	}
	
	
}