<?php
require_once 'Neri/Db/Table/Abstract.php';

class Neri_Db_Table_TArray extends Neri_Db_Table_Abstract
{

    protected $_name	= 't_array';

    protected $_primary = 'key';

    protected $_sequence = true;

}
