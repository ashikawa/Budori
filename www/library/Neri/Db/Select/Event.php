<?php
require_once 'Budori/Db/Select.php';

class Neri_Db_Select_Event extends Budori_Db_Select
{

    public function init()
    {
        $this->from( array( 'e' => 'event' ));
    }

    public function setDefault()
    {
        return $this->equal('e.status',true);
    }

}
