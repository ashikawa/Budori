<?php
require_once 'Budori/Db/Select.php';

class Neri_Db_Select_Member extends Budori_Db_Select
{

    public function init()
    {
        $this->from( array( 'm' => 'member' ));
    }

    /**
     * Enter description here...
     * @return Neri_Db_Select_Member
     */
    public function setKey( $key )
    {
        return $this->equal('m.key',$key);
    }
    /**
     * Enter description here...
     * @return Neri_Db_Select_Member
     */
    public function setDefault()
    {
        return $this->equal('m.status', true);
    }

    /**
     * Enter description here...
     * @return Neri_Db_Select_Member
     */
    public function setMixiId( $id )
    {
        return $this->equal('m.mixi_id',$id);
    }

    public function orderById($spec = Zend_Db_Select::SQL_ASC)
    {
        return $this->orders( array('m.key' => $spec) );
    }
}
