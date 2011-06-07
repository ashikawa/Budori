<?php
require_once 'Budori/Db/Select.php';

class Neri_Db_Select_Address extends Budori_Db_Select 
{
	
	public function init()
	{
		$this->from( array( 'a' => 'address' ));
	}
	
	public function setDefault()
	{
		return $this->equal('a.status',true);
	}
	
}
