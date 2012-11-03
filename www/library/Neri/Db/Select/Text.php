<?php
require_once 'Budori/Db/Select.php';

class Neri_Db_Select_Text extends Budori_Db_Select
{

    public function init()
    {
        $this->from( array( 'te' => 'text' ) );
    }

    /**
     * Enter description here...
     *
     * @param  unknown_type        $key
     * @return Neri_Db_Select_Text
     */
    public function setKey( $key )
    {
        return $this->equal('te.key', $key);
    }

    /**
     * 全文検索(webquery)用のメソッド
     * textsearch_ja　モジュールインストール済みの　postgres 8.3 以上のみ対応
     *
     * @see http://textsearch-ja.projects.postgresql.org/textsearch_ja.html#web_query
     * @todo 記号のエスケープ処理
     *
     * @param  string           $key
     * @param  string           $value
     * @return Budori_Db_Select
     */
    public function textSearch( $value )
    {
        if ( !( $this->_adapter instanceof Zend_Db_Adapter_Pdo_Pgsql ) ) {

            throw new Zend_Db_Select_Exception("this method is supported only 'Zend_Db_Adapter_Pdo_Pgsql'");
        }

        require_once 'Budori/Util/String.php';
        $value = Budori_Util_String::mbTrim($value);

        return $this->where("\"te\".\"tsv\" @@ to_tsquery('japanese',web_query( ? ) )", $value );
    }

    public function orderByKey($spec = Zend_Db_Select::SQL_ASC)
    {
        return $this->orders( array('te.key' => $spec) );
    }
}
