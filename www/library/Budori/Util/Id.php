<?php
/**
 * 乱数、ハッシュ関係Util
 */
class Budori_Util_Id 
{
	
	private final function __construct()
	{}
	
	/**
	 * ハッシュ値(MD5)の生成
	 *
	 * @param string $prefix
	 * @return string
	 */
	public static function getRandomMd5()
	{
	    return md5( self::getRandomSeed() );
	}
	
	/**
	 * ハッシュ値(SHA1)の生成
	 *
	 * @param string $prefix
	 * @return string
	 */
	public static function getRandomSha1()
	{
	    return sha1( self::getRandomSeed() );
	}
	
	/**
	 * 乱数の生成
	 * @return integer
	 */
	public static function getRandomSeed()
	{
		mt_srand();
	    return mt_rand();
	}
}
