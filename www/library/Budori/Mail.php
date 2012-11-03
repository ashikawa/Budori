<?php
require_once 'Zend/Mail.php';

/**
 * Zend_Mailの日本語対応
 * 各メソッドにmb_convert_encodingの処理を追加した物
 *
 * charset ISO-2022-JP-MS　は半角カナ(機種依存文字)対策
 *
 * ソフトバンク携帯で文字化けのため、ISO-2022-JPに戻して
 * 半角カタカナは全角に置き換えた方が良いかも。
 * (あとは、変換は ISO-2022-JP-MS でやって、ヘッダーには ISO-2022-JP と書いておくとか)
 * @see http://d.hatena.ne.jp/t_komura/20091101/1257080705
 */
class Budori_Mail extends Zend_Mail
{

    /**
     * from エンコードタイプ( mb_convert_encoding )
     * @var string
     */
//    protected $_fromEncoding = 'SJIS,EUC-JP,JIS,UTF-8,ASCII';
    //設定は application.ini (php.ini) に移動
    protected $_fromEncoding = 'auto';

    /**
     * mb_convert_charの引数
     * @var unknown_type
     */
    protected $_toEncodeing	= 'ISO-2022-JP-MS';

    protected $_headerEncoding = Zend_Mime::ENCODING_BASE64;

    /**
     * コンストラクタ
     */
    public function __construct($charset = 'ISO-2022-JP')
    {
        parent::__construct($charset);
    }

    /**
     * メールのエンコードタイプの設定
     * @param string $string
     */
    public function setToEncoding($string)
    {
        $this->_toEncodeing = $string;
    }

    /**
     * ソースのエンコードの設定
     * @param string $string
     */
    public function setFromEncoding($string)
    {
        $this->_fromEncoding = $string;
    }

    /**
     * 本文のセット
     * @param  string      $txt
     * @return Budori_Mail Provides fluent interface
     */
    public function setBodyText($txt, $charset = null, $encoding = Zend_Mime::ENCODING_7BIT )
    {
        $txt = $this->_convertText( $txt );

        return parent::setBodyText($txt, $charset, $encoding );
    }

     /**
      * 本文のセット(HTMLメール)
     * @param  string      $txt
     * @return Budori_Mail Provides fluent interface
     */
    public function setBodyHtml($html, $charset = null, $encoding = Zend_Mime::ENCODING_7BIT)
    {
        $html = $this->_convertText($html);

        return parent::setBodyHtml($html, $charset, $encoding );
      }

    /**
     * 文字のコンバート
     * 改行コードも修正する。
     *
     * @param  string $string
     * @return string
     */
    protected function _convertText($string)
    {
        $string = str_replace("\r\n", "\n", $string);
        $string = str_replace("\r", "\n", $string);

        return mb_convert_encoding(
                    $string,
                    $this->_toEncodeing,
                    $this->_fromEncoding
                );
     }

     /**
      * @override
      *
      * @param unknown_type $value
      * @return unknown
      */
    protected function _encodeHeader($value)
    {
        if (Zend_Mime::isPrintable($value) === false) {
            if ($this->getHeaderEncoding() === Zend_Mime::ENCODING_QUOTEDPRINTABLE) {
                $value = Zend_Mime::encodeQuotedPrintableHeader($value, $this->getCharset(), Zend_Mime::LINELENGTH, Zend_Mime::LINEEND);
            } else {

                $value = $this->_encodeBase64Header($value);
            }
        }

        return $value;
    }

    /**
     * Mimeヘッダ用base64エンコード
     *
     * @param  string $string
     * @return string
     */
     protected function _encodeBase64Header($string)
     {
        return mb_encode_mimeheader(
                    $string,
                    $this->_toEncodeing
                );
     }
}
