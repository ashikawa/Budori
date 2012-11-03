<?php
require_once 'Zend/Validate/Regex.php';

/**
 * カタカナかどうか調べる
 */
class Budori_Validate_Katakana extends Zend_Validate_Regex
{

    const NOT_KATAKANA = 'notKatakana';

    /**
    　* Regular expression pattern
    　* @var string
     */
    protected $_regex = '/[ァ-ヶ]+/';

    /**
     * preg flag
     * UTF-8に対応させるフラグ
     * @var string
     */
    protected $_flag = 'u';

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $pattern = $this->_regex . $this->_flag;
        parent::__construct($pattern);
    }

    /**
    　* @var array
    　*/
    protected $_messageTemplates = array(
        self::NOT_KATAKANA => "'%value%' does not Katakana"
    );

}
