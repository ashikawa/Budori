<?php

/**
 * 拡張されたReflectionクラス
 */
class Budori_Reflection extends ReflectionClass 
{
	
	/**
	 * newInstanceArgs　のパラメータを連想配列にする。
	 * ( 並び順をパラメータの名前を使ってソートする )
	 * Factoryパターンでコンストラクタの引数の数が定まらない時に有用？　かも。
	 * 
	 * @param array $args
	 * @return mixed
	 * @throws ReflectionException
	 */
	public function newInstanceArgs( $args = array() )
	{
		$const	= new ReflectionMethod( $this->name, $this->getConstructor()->name );
		$params	= $const->getParameters();
		
		$_tmp = array();
		
		foreach ( $params as $_val ){
			
			$name = $_val->name;
			
			if( isset($args[$name]) ){
				$_tmp[$name] = $args[$name];
			}else{
				
				if( !$_val->isDefaultValueAvailable() ){
					throw new ReflectionException("Default Value Not Available '$name' ");
				}
				
				break;
			}
		}
		
		if( count($args) != count($_tmp) ){
			throw new ReflectionException( 'wrong paramater' );
		}
		
		return parent::newInstanceArgs($_tmp);
	}
}