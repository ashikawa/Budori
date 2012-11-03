<?php
require_once 'Neri/Auth/Adapter/DbAbstract.php';
/**
 * MixiOpenId 用の認証クラス
 */
class Neri_Auth_Adapter_Mixi extends Neri_Auth_Adapter_DbAbstract
{

    protected $_mixiId;

    public function setMixiId( $mixiId )
    {
        if (preg_match("/^https:\/\/id\.mixi\.jp\/(?P<id>\d+)$/iu", $mixiId, $matches)) {
            $id = $matches['id'];
        } else {
            $id = $mixiId;
        }

        $this->_mixiId = $id;

        return $this;
    }

    protected function _authenticateCreateSelect()
    {
        require_once 'Neri/Db/Select/Member.php';
        $query = new Neri_Db_Select_Member($this->_db);
        $query->setMixiId( $this->_mixiId )
                ->setDefault();

        return $query;
    }

    protected function _authenticateValidateResultSet( $result )
    {
        require_once 'Zend/Auth/Result.php';

        if ( count($result) == 1 ) {

            $this->_resultRow = $result[0];

            $this->_code = Zend_Auth_Result::SUCCESS;

            $this->setIdentity( $result[0]->key );

        } elseif (count($result) == 0) {

            $this->_code = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;

        } elseif (count($result) > 0) {

            $this->_code = Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
        }
    }
}
