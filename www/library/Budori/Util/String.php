<?php
/**
 * 文字列処理
 */
class Budori_Util_String
{
	
	const NUMBER	= '0123456789';
	
	const ALPHA		= 'abcdefghijklmnopqrstuvwxyz';
		
	/**
	 * テキストにパラメータを埋め込む
	 * @param string $string
	 * @param array $params
	 * @return Budori_String
	 */
	public static function burialtParams( $string, $params )
	{
		if( count($params) > 0 ){
			foreach ( $params as $_key => $_value ){
				$string = @preg_replace("/%$_key%/", $_value, $string);
			}
		}
		
		return $string;
	}
	
	
	/**
	 * マルチバイト文字が使われているか調べる
	 * @param string $text
	 * @return boolean
	 */
	public static function useMultiBite($string)
	{
		return (strlen($string) != mb_strlen($string) );
	}
	
	
	/**
	 * マルチバイト文字列を配列に分割
	 * @return string
	 */
	public static function getCharArray($string)
	{
		
		if( preg_match_all('/./u',$string, $matches) === false ){
			throw new Budori_Exception('preg error');
		}
		
		return $matches;
	}
	
	/**
	 * ランダムな文字列を生成する。
	 * @param int $length 必要な文字列長。省略すると 8 文字
	 * @return String ランダムな文字列
	 */
	public static function randomString($length = 8)
	{
		$list = self::NUMBER . self::ALPHA . strtoupper(self::ALPHA);
		mt_srand();
		
		$ret = "";
		for($i=0; $i < $length; $i++){
			$ret .= $list[mt_rand(0, strlen($list) - 1)];
		}
		
		return $ret;
	}
	
}
