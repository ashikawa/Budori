<?php
require_once 'Zend/Auth/Adapter/Interface.php';
require_once 'Zend/Auth/Result.php';
require_once 'Zend/Db/Adapter/Abstract.php';

/**
 * DB認証用　抽象クラス
 */
abstract class Neri_Auth_Adapter_DbAbstract implements Zend_Auth_Adapter_Interface
{

    /**
     * Enter description here...
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;

    protected $_identity;

    protected $_resultRow = null;

    protected $_code;

    /**
     * Enter description here...
     * @var array
     */
    protected $_messages;

    /**
     * コンストラクタ
     * @param Zend_Db_Adapter_Abstract $db
     */
    public function __construct( Zend_Db_Adapter_Abstract $db )
    {
        $this->_db = $db;

        $ref = new ReflectionClass('Zend_Auth_Result');
        $this->_messages = array_flip($ref->getConstants());
    }

    /**
     * Enter description here...
     *
     * @param  string                     $identity
     * @return Neri_Auth_Adapter_Abstract
     */
    public function setIdentity( $identity )
    {
        $this->_identity = $identity;

        return $this;
    }

    public function getIdentity()
    {
        return $this->_identity;
    }

    public function authenticate()
    {

        $dbSelect = $this->_authenticateCreateSelect();
        $resultIdentities = $this->_authenticateQuerySelect($dbSelect);

        $messages = array(
            $this->_messages[$this->_code],
        );

        return new Zend_Auth_Result($this->_code, $this->_identity, $messages );
    }

    abstract protected function _authenticateCreateSelect();

    abstract protected function _authenticateValidateResultSet( $result );

    protected function _authenticateQuerySelect( Zend_Db_Select $query )
    {
        $result = $this->_db->fetchAll( $query );
        $this->_authenticateValidateResultSet( $result );
    }

    public function getResultRowObject()
    {
        return $this->_resultRow;
    }

    public function getMessages()
    {
        return $this->_messages;
    }
}
