<?php

/**
 * 各種定数をまとめたクラス
 */
class Budori_Const 
{
	/**
	 * コンストラクタ
	 * インスタンスは生成させない
	 */
	private final function __construct()
	{}
	
	/**
	 * 正規表現のパターン　URL
	 */
	const PATTERN_URL			= "https?:\/\/[-_.!~*'\(\)[[:alnum:];\/?:@&=+$,%#]+";
	
	/**
	 * 正規表現のパターン　メールアドレス
	 */
	const PATTERN_MAILADDR	= "[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,4}";
	
	
}