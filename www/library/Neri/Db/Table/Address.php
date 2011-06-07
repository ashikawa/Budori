<?php
require_once 'Neri/Db/Table/Abstract.php';

class Neri_Db_Table_Address extends Neri_Db_Table_Abstract 
{
	
    protected $_name	= 'address';
    
	protected $_primary = 'key';
    
	protected $_sequence = true;
	
}
