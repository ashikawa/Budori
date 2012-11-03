<?php

/**
 * PDFの作成
 * 使い方としては全部書くのではなくて、一度作ったファイルを読みこんでから
 * 動的項目を埋めるのが正しい使い方な気がする。
 *
 * $pdf = new Zend_Pdf();
 * 			↓
 * $pdf = Zend_Pdf::parse('filename.....');
 *
 * php.ini　の　memory_limit　に注意。 FatalErrorなんたらが出たら増やす。
 *
 * @see http://framework.zend.com/manual/ja/zend.pdf.html
 */
class TestPdf
{
    /**
     * Pdf Object
     * @var Zend_Pdf
     */
    protected $_pdf;

    public function __construct()
    {
        $this->_pdf = new Zend_Pdf();
    }

    public function create()
    {
        $this->_pdf->pages[] = new TestPdf_Page1();

        return $this->_pdf->render();
    }
}
