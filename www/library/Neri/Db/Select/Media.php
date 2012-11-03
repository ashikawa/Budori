<?php
require_once 'Budori/Db/Select.php';

class Neri_Db_Select_Media extends Budori_Db_Select
{

    public function init()
    {
        $this->from( array( 'me' => 'media' ) );
    }

    /**
     * Enter description here...
     *
     * @param  unknown_type         $key
     * @return Neri_Db_Select_Media
     */
    public function setKey( $key )
    {
        return $this->equal('me.key', $key);
    }

    /**
     * Enter description here...
     *
     * @return Neri_Db_Select_Media
     */
    public function setDefault()
    {
        return $this->equal( 'me.status', true );
    }

    /**
     * Enter description here...
     *
     * @return Neri_Db_Select_Media
     */
    public function setOwner( $owner )
    {
        return $this->equal('me.owner', $owner);
    }

    /**
     * Enter description here...
     *
     * @param  unknown_type         $ext
     * @return Neri_Db_Select_Media
     */
    public function setExt( $ext )
    {
        return $this->equal('me.ext',$ext);
    }
}
