<?php
require_once 'Zend/Db/Adapter/Abstract.php';
require_once 'Zend/Db/Select.php';

/**
 * Zend_Db_Select の拡張
 * 汎用的なメソッドの定義
 */
class Budori_Db_Select extends Zend_Db_Select
{

    /**
     * Class constructor
     * @param Zend_Db_Adapter_Abstract $adapter
     */
    public function __construct( Zend_Db_Adapter_Abstract $adapter = null )
    {
        parent::__construct($adapter);
        $this->init();
    }

    public function init()
    {}

    /**
     * 追加の場合は普通に colmns。
     * こっちは初期化処理を含む。
     *
     * @param  array|string|Zend_Db_Expr $cols            The columns to select from this table.
     * @param  string                    $correlationName Correlation name of target table. OPTIONAL
     * @return Budori_Db_Select          This Budori_Db_Select object.
     */
    public function setColumns($cols = '*', $correlationName = null)
    {
        $this->reset( Zend_Db_Select::COLUMNS );

        return parent::columns( $cols, $correlationName );
    }

    /**
     * count(*) 句
     * @param  string           $col
     * @return Budori_Db_Select
     */
    public function count( $col = '*', $name='count' )
    {
        return $this->setColumns("count($col) as $name");
    }

    /**
     * where 句の追加
     *
     * @param  string           $key
     * @param  string           $value
     * @return Budori_Db_Select
     */
    public function equal( $key, $value, $type = null )
    {
        $adapter = $this->getAdapter();
        $key = $adapter->quoteIdentifier($key);

        return $this->where("$key = ?", $value, $type);
    }

    /**
     * where 句の追加
     *
     * @param  string           $key
     * @param  string           $value
     * @return Budori_Db_Select
     */
    public function notEqual( $key, $value, $type = null )
    {
        $adapter = $this->getAdapter();
        $key = $adapter->quoteIdentifier($key);

        return $this->where("$key != ?", $value, $type);
    }

    /**
     * where 句を連想配列で追加
     * $key = $value AND ...
     *
     * @param  array            $data
     * @return Budori_Db_Select
     */
    public function equals( Array $data )
    {
        foreach ($data as $key => $value) {

            $this->equal($key, $value);
        }

        return $this;
    }

    /**
     * in 句の追加
     *
     * @param  string           $key
     * @param  array            $data
     * @param  mixed            $type OPTIONAL the SQL datatype name, or constant, or null.
     * @return Budori_Db_Select
     */
    public function in( $key, $data, $type = null )
    {
        $tmp = array();
        $adapter = $this->getAdapter();

        foreach ($data as $_val) {

            $tmp[] = $adapter->quote($_val, $type);
        }

        $str = implode(',',$tmp);

        $key = $adapter->quoteIdentifier($key);

        return $this->where("$key IN ($str)");
    }

    /**
     * Like
     * @param  string           $key
     * @param  string|array     $values $format 内の %s を複数指定する場合は配列
     * @param  string           $format sprintf 用のフォーマット %s にエスケープされた文字が入る
     * @param  string           $char エスケープ文字
     * @return Budori_Db_Select
     */
    public function like($key, $values, $format="%%%s%%", $char="@")
    {
        $adapter = $this->getAdapter();
        $key     = $adapter->quoteIdentifier($key);

        if (!is_array($values)) {
        	$values = array($values);
        }
        
        $args = array($format);
        
        foreach ($values as $_v) {
        	$args[] = $this->_likeEscape($_v, $char);
        }
        
        $statement = call_user_func_array("sprintf", $args);
        
        return $this->where("$key LIKE ? ESCAPE '$char'", $statement);
    }

    /**
     * Like foward
     * @param  string           $key
     * @param  string           $value
     * @return Budori_Db_Select
     */
    public function likeFoward($key, $value)
    {
        return $this->like($key, $value, "%s%%");
    }

    /**
     * Like backword
     * @param  string           $key
     * @param  string           $value
     * @return Budori_Db_Select
     */
    public function likeBackword($key, $value)
    {
        return $this->like($key, $value, "%%%s");
    }

    /**
     * @param  string $key
     * @param  string $char エスケープ文字
     * @return string
     */
    protected function _likeEscape($value, $char)
    {
        $search  = array($char, "_", "%");
        $replace = array();

        foreach ($search as $_v) {
            $replace[] = $char . $_v;
        }

        return str_replace($search, $replace, $value);
    }

    /**
     * GT
     * @param  string           $key
     * @param  string           $value
     * @return Budori_Db_Select
     */
    public function greaterThan($key, $value)
    {
        $adapter = $this->getAdapter();
        $key = $adapter->quoteIdentifier($key);

        return $this->where("$key > ?", $value);
    }

    /**
     * LT
     * @param  string           $key
     * @param  string           $value
     * @return Budori_Db_Select
     */
    public function lessThan($key, $value)
    {
        $adapter = $this->getAdapter();
        $key = $adapter->quoteIdentifier($key);

        return $this->where("$key < ?", $value);
    }

    /**
     * order を配列で指定
     *
     * @param array $orders
     *	key		項目名  etc) m.id
     *	value	ASC | DESC
     * @return Budori_Db_Select
     */
    public function orders(Array $orders)
    {
        $ord = array();

        foreach ($orders as $key => $value) {
            $ord[] = "$key $value";
        }

        return parent::order($ord);
    }
}
