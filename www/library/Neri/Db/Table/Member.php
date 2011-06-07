<?php
require_once 'Neri/Db/Table/Abstract.php';


class Neri_Db_Table_Member extends Neri_Db_Table_Abstract 
{
    protected $_name	= 'member';
    
	protected $_primary = 'key';
	
    protected $_sequence = false;
}
