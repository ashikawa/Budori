<?php
require_once 'Budori/Db/Select.php';

class Neri_Db_Select_Url extends Budori_Db_Select
{

    public function init()
    {
        parent::init();
        $this->from( array( 'u' => 'url' ));
    }

    /**
     * Enter description here...
     * @return Neri_Db_Select_Url
     */
    public function setDefault()
    {
        return $this->equal('u.status',true);
    }

    /**
     * Enter description here...
     * @return Neri_Db_Select_Url
     */
    public function searchBodyText($value)
    {
        return $this->where("to_tsvector('japanese',\"u\".\"statement\") @@ to_tsquery('japanese',web_query( ? ) )", $value );
    }

    public function orderByDefault($spec = Zend_Db_Select::SQL_DESC)
    {
        return $this->orders( array('u.num' => $spec) );
    }
}
