<?php
require_once 'Budori/Db/Select.php';


class Neri_Db_Select_TArray extends Budori_Db_Select 
{
	
	public function init()
	{
		$this->from( array( 'ta' => 't_array' ));
	}
}
