<?php
/**
 * @see Neri_Controller_Plugin_Url
 */
class Maybe_Url extends Maybe
{
    /**
     * 抽出するリストの数
     * パフォーマンスに合わせて調整
     */
    const LIMIT_OF_LIST = 1000;

    public function __construct()
    {
        parent::__construct();
        $this->_loadList();
    }

    /**
     * リストの抽出クエリ
     */
    protected function _loadList()
    {
        $db = $this->getDbAdapter();

        $select = new Neri_Db_Select_Url($db);

        $select->setColumns('url')
                            ->setDefault()
                            ->limit(self::LIMIT_OF_LIST)
                            ->orderByDefault();

        $result = $db->fetchAll($select);

        $list = array_map(array($this,'_loadFilter'), $result);

        $this->setList($list);
    }

    /**
     * クエリ結果のフィルタリング要メソッド
     *
     * @param  stdClass $row
     * @return string
     */
    protected function _loadFilter($row)
    {
        return $row->url;
    }

    /**
     * 検索リストへの登録
     * @param string $url
     */
    public function addList($url, $title, $body = null)
    {
        $db = $this->getDbAdapter();

        $table = new Neri_Db_Table_Url($db);

        $upData = array(
            'num'		=> new Zend_Db_Expr('"num" + 1'),
            'title'		=> $title,
            'body'		=> $body,
            'statement'	=> "$title $body"
        );

        $url = trim($url);

        $ret = $table->update( $upData, $db->quoteInto('"url" = ?', $url) );

        //$ret には、更新された行数が入る
        if ($ret === 0) {
            $insDate = array(
                'url'		=> $url,
                'title'		=> $title,
                'body'		=> $body,
                'statement'	=> "$title $body"
            );

            $ret = $table->insert($insDate);
        }
    }

    /**
     * Enter description here...
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDbAdapter()
    {
        return Budori_Db::factory();
    }
}
