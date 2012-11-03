<?php
/**
 * 画像の管理を行うモデル
 */
class Media
{
    /**
     * Enter description here...
     * @var Neri_Db_Table_Media
     */
    protected $_table;

    public function __construct(Zend_Db_Adapter_Abstract $db = null)
    {
        $this->_table = new Neri_Db_Table_Media($db);
    }

    /**
     * Tableオブジェクトの生成
     * @return Zend_Db_Table_Abstract
     */
    public function getTable()
    {
        return $this->_table;
    }

    public function create()
    {}

    public function read( $key )
    {
        $mediaTable = $this->getTable();

        return $mediaTable->find($key);
    }

    /**
     * 画像の削除
     *
     * @param  string  $memberId
     * @param  string  $key
     * @return integer
     */
    public function delete( $key )
    {
        $mediaTable = $this->getTable();

        $conditions = array(
            $mediaTable->getAdapter()->quoteInto('key = ?', $key),
        );
        $atters = array(
            'status' => false,
        );

        return $mediaTable->update($atters, $conditions);
    }

    public static function encodeData($value)
    {
        return base64_encode($value);
    }

    public static function decodeData($value)
    {
        return base64_decode($value);
    }
}
