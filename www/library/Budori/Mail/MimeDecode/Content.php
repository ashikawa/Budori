<?php
/**
 * Mail_mimeDecode::decode()　の結果を格納するクラス
 * メール本文、添付ファイルごとにインスタンスを持つ
 */
class Budori_Mail_MimeDecode_Content
{

    /**
     * ヘッダーの配列
     * @var array
     */
    public $headers;

    /**
     * コンテンツタイプ1
     * @var string
     */
    public $ctype_primary;

    /**
     * コンテンツタイプ2
     * @var string
     */
    public $ctype_secondary;

    /**
     * その他パラメータ
     * charset, name etc...
     * @var array
     */
    public $ctype_parameters;

    /**
     * データ本体
     * @var binary | string
     */
    public $body;

    /**
     * 初期化
     * @param stdClass $data
     */
    public function __construct( $data )
    {
        foreach ($data as $_key => $value) {
            $this->$_key = $value;
        }
    }

    /**
     * コンテンツタイプの取得
     * ex) 'text/plain'
     * @return string
     */
    public function getContentType()
    {
        return $this->ctype_primary . '/' . $this->ctype_secondary;
    }

    /**
     * パラメータの取得
     * @param  string  $str
     * @return boolean
     */
    public function getParam( $str=null )
    {
        if( is_null($str) ) return $this->ctype_parameters;

        if ( isset($this->ctype_parameters[$str]) ) {
            return $this->ctype_parameters[$str];
        }

        return false;
    }

    /**
     * データの保存
     * @param  string  $path
     * @return boolean
     */
    public function save( $path )
    {
        if ( ( $fp = fopen($path, "w+b") ) != null ) {
            echo fwrite( $fp, $this->body );
            fclose( $fp );

            return true;
        }

        return false;
    }

    /**
     * データのサイズを返す ( 単位はバイト )
     * @return integer
     */
    public function getSize()
    {
        return strlen( $this->body );
    }

    /**
     * (あれば)ファイル名を取得
     * @return string | null
     */
    public function getName()
    {
        if ( isset($this->ctype_parameters['name']) ) {
            return $this->ctype_parameters['name'];
        }

        return null;
    }

}
