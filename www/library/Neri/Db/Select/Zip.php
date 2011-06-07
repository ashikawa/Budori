<?php
require_once 'Budori/Db/Select.php';


class Neri_Db_Select_Zip extends Budori_Db_Select 
{
	
	public function init()
	{
		$this->from( array( 'z' => 'zip' ));
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $code
	 * @return Neri_Db_Select_Zip
	 */
	public function codeLike( $code )
	{
		return $this->like( 'z.code', $code."%" );
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $code
	 * @return Neri_Db_Select_Zip
	 */
	public function setPref($pref)
	{
		return $this->where('z.pref = ?',$pref);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $code
	 * @return Neri_Db_Select_Zip
	 */
	public function setCity($city)
	{
		return $this->where('z.city = ?', $city);
	}
	
	
	public function orderByCode($spec = Zend_Db_Select::SQL_ASC)
	{
		return $this->orders( array('z.code' => $spec) );
	}
}
