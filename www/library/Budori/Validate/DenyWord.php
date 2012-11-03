<?php
require_once 'Zend/Validate/Abstract.php';

/**
 * 禁止ワードリストに登録されているかどうか
 */
class Budori_Validate_DenyWord extends Zend_Validate_Abstract
{

    /**
     * 禁止ワードリスト
     * @var array
     */
    protected $_denylist = null;

    /**
     * 共通禁止ワードリスト
     * @var array
     */
    protected static $_defaultDenyList = null;

    const DENY_EXISTS = 'denyExists';

    protected $_messageTemplates = array(
        self::DENY_EXISTS   => "deny word exists",
    );

    /**
     * 禁止ワードリストの初期化
     */
    public function __construct($list=null)
    {
        if ($list instanceof Zend_Config) {
            $list = $list->toArray();
        }

        if ( isset( $list['list'] ) ) {
            $list = $list['list'];
        }

        $this->setDenyList( $list );
    }

       /**
        * 禁止ワードリストのセット
     * @param array $list
     */
    public function setDenyList($list)
       {
           $this->_denylist = $list;
       }

       /**
        * get denylist (or static list)
        * @return array
        */
       public function getDenyList()
       {
           if ( !empty($this->_denylist) ) {
               return $this->_denylist;
           }

           return self::$_defaultDenyList;
       }

       /**
        * set static deny list
        * @param array $list
        */
       public static function setDefaultDenyList($list)
       {
           self::$_defaultDenyList = $list;
       }

       /**
        * get static denylist
        * @return array
        */
       public static function getDefaultDenyList()
       {
           return self::$_defaultDenyList;
       }

    /**
     * バリデート実行
     *
     * @param  stging  $value
     * @return boolean
     *
     * @uses DenywordDataAccess::select()
     */
    public function isValid($value)
    {
        $valueString = (string) $value;

        $this->_setValue($valueString);

        if ('' === $valueString) {
            return true;
        }

        $words = $this->getDenyList();

        if ( empty( $words ) ) {
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception('not found denyWordList');
        }

        foreach ($words as $var) {
            if ( false !== stristr( $value,$var ) ) {

                $this->_error(self::DENY_EXISTS);

                return false;
            }
        }

        return true;
    }

}
