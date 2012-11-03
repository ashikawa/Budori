<?php
class TestPdf_Page1 extends Zend_Pdf_Page
{

    public function __construct()
    {
        parent::__construct(Zend_Pdf_Page::SIZE_A4);

        $this->_create();
    }

    protected function _create()
    {
        $this->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 36);
        $this->drawText('Hello world!', 72, 720);

        $this->setFont(Zend_Pdf_Font::fontWithPath( ROOT . '/data/font/ipag.ttf'), 12);

        $this->drawText( "今日は " . date("Y年m月d日 H:i:s"), 72, 700, 'UTF-8' );

    }
}
