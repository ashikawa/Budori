<?php
class Media_Search extends Neri_Db_Select_Media
{
    /**
     * Enter description here...
     * @var Neri_Db_Select_Member
     */
    protected $_member;

    public function init()
    {
        parent::init();

        $this->setColumns(array('me.key','me.name','me.ext'));

        //memberテーブルのクエリ
        $member = new Neri_Db_Select_Member( $this->getAdapter() );
        $member->setDefault();

        //テーブルの結合
        $this->join( array('m' => $member), '"me"."owner" = "m"."key"', array('m.key') );

        $this->_member = $member;
    }

    /**
     * Enter description here...
     *
     * @param  string            $owner
     * @return Media_SearchModel
     */
    public function setOwner($owner)
    {
        $this->_member->setKey($owner);

        return parent::setOwner($owner);
    }
}
