<?php

/**
 * メディア出力用コントローラ
 *
 * @todo pdf,csvで、ファイル名のエンコードが上手くいかない問題
 * -> ブラウザ互換、URLに含めるファイル名、ファイルヘッダーで送信するファイル名の日本語、記号のエンコード問題。
 *
 * @todo cache controll 挙動確認。
 */
class MediaController extends Budori_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->disableLayout();
    }

    /**
     * Qrコードライブラリの使用例
     */
    public function qrcodeAction()
    {
        $this->setNoRender();

        $params = array_merge(
            array(
                'str'		=> '',
                'size'		=> '3',
//				'version'	=> '1',
//				'zone'		=> '5',
            ),$this->_getAllParams());

        require_once 'qrcode/qrcode_img.php';
        $qrcode = new Qrcode_image();

//		$qrcode->set_quietzone($params['zone']);
//		$qrcode->set_qrcode_version($params['version']);
        $qrcode->set_module_size($params['size']);

        $this->getResponse()->setHeader('Content-Type','image/gif');

        if ($this->getRequest()->isGet()) {
            $str = htmlspecialchars_decode(urldecode($params['str']));
        } else {
            $str = $params['str'];
        }

        $qrcode->qrcode_image_out( $str, "gif");
    }

    /**
     * 画像出力、キャッシュの作成。
     *　URLの自由な張替えサンプル。
     */
    public function imageAction()
    {
        $this->setNoRender();

        $params = $this->_getAllParams();

         $db = Budori_Db::factory();

         $select = new Neri_Db_Select_Media($db);
         $select->setExt($params['ext'])
                 ->setKey($params['key'])
                 ->setOwner($params['owner'])
                 ->setDefault();

         $image = new Media_Image($select);

        $content = $image->createImage($this->_getParam('mode',null));

        $output = $content->saveImage();

        $this->getResponse()
                ->setHeader("Content-Type", $content->getMime())
                ->setHeader('Content-Length', strlen($output));

        $this->getResponse()->setBody($output);

        $this->_createTmp($output);
    }

    public function imageStringJpgAction()
    {
        $this->setNoRender();

        $image	= new Budori_Image( new Budori_Image_Resource(ROOT . '/public/img/tile.jpg'));

        $color	= array(255,255,255);
        $font	= ROOT . "/data/font/ipamp.ttf";
        $string	= 'sample string';

        $image->setText($string, 20, 0, 50, 50, $color, $font);

        $output = $image->getResource()->saveImage();

        $this->getResponse()
                ->setHeader("Content-Type", $image->getResource()->getMime())
                ->setHeader('Content-Length', strlen($output));

        $this->getResponse()->setBody($output);

//		$this->_createTmp($output);
    }

    public function pdfAction()
    {
        $this->setNoRender();

        $filename = $this->_getParam('file','sample.pdf');

        $pdf = new TestPdf();

        $output = $pdf->create();

        $filesize = strlen($output);

        $this->getResponse()
                ->setHeader('Content-Type','application/pdf')
                ->setHeader('Expires', '0')
//	       		->setHeader('Cache-Control', 'must-revalidate,post-check=0,pre-check=0')
//	        	->setHeader('Pragma', 'private')
                   ->setHeader('Cache-Control', 'no-cache')
                   ->setHeader('Pragma', 'no-cache')

                ->setHeader('Content-Disposition', "inline; filename=$filename")
                ->setHeader('Content-Length', $filesize)
                ->setBody($output);
    }

    /**
     * @todoこの辺もContextSwitch かモデルにまとめる。
     */
    public function csvAction()
    {
        $filename = $this->_getParam('file','sample.csv');

         // ダミーデータ
        $rows = array(
            array('column1', 'column2', 'column3', 'column4'),
            array('column1', 'column2', 'column3', 'column4'),
            array('column1', 'column2', 'column3', 'column4'),
        );

//		$csvOutputString = mb_convert_encoding($csvOutputString,"SJIS","auto");
        $rows = array_map(array($this,"_csvConvert"),$rows);

        $this->view->assign('result', $rows);

        $this->getResponse()
                ->setHeader('Content-Type','text/csv')
                ->setHeader('Expires', '0')
//	       		->setHeader('Cache-Control', 'must-revalidate,post-check=0,pre-check=0')
//	        	->setHeader('Pragma', 'private')

                   ->setHeader('Cache-Control', 'no-cache')
                ->setHeader('Pragma', 'no-cache')
                ->setHeader('Content-Disposition', "attachment; filename=$filename");
    }

    protected function _csvConvert($inputString)
    {
        $inputString = str_replace('"','""',$inputString);

        return "\"$inputString\"";
    }

    /**
     * iCalender 形式
     * @todo 専用のエスケープ関数　 クオートする文字は　';' ':' ','
     * @see http://www.asahi-net.or.jp/~CI5M-NMR/iCal/rfc2445.txt
     * @todo 自動改行はビューフィルタ? (or Smarty Filter)
     */
    public function calenderIcsAction()
    {
        $this->view->autoescape(false);

        $filename = "calender.ics";

        $this->getResponse()
//				->setHeader('Content-Type','text/calendar')
                ->setHeader('Content-Type','text/plain')
                ->setHeader('Content-Disposition', "attachment; filename=$filename");
    }

    public function calenderXmlAction()
    {}

    /**
     * 画像ファイルなどのキャッシュを生成する
     * @param mixed $data
     */
    protected function _createTmp( $data )
    {
        $request = $this->getRequest();

        $docroot	= $request->getServer('DOCUMENT_ROOT');
        $path		= $docroot . $request->getPathInfo();

        $dir = dirname($path);

        if ( !is_dir($dir) ) {
            mkdir($dir);
        }
        file_put_contents( $path, $data );
    }

    /**
     * いろいろ修正
     */
    public function feedAtomAction()
    {
        $this->setNoRender();

        $db = Budori_Db::factory();
        $select = new Neri_Db_Select_Text($db);

        $select->orderByKey()
                ->limit(20);

        $entries = $db->fetchAll($select);

        /**
         * @todo ここ、configにしてしまおう。
         */
        $array = array(
            'title'			=> 'novels',						// required
            //これは rss 自体のURLではなくて、リンク元のURL
            'link'			=> 'http://me.example/',			// required
            'lastUpdate'	=> time(DATE_ATOM),					// required
            //'published'	=> 'timestamp of the publication date',
            'charset'		=> 'utf-8',							// required
            'description'	=> 'rssのテスト',
            'author'		=> 'a.shigeru',
            'email'			=> 'a.shigeru@gmail.com',
             //'webmaster'	=> 'email address for person responsible for technical issues'
            'copyright'		=> 'mine',
             //'image'		=> 'url to image',
            'generator'   => 'Zend_Feed',
            'language'		=> 'ja',
             //'ttl'			=> 'how long in minutes a feed can be cached before refreshing',
             //'rating'      => 'The PICS rating for the channel.', // optional, ignored if atom is used
            //'cloud'       => array() .. // ?????
            //'textInput'   => array() .. // ?????
            //'skipHours'   => array() .. // ?????
            //'skipDays '   => array() .. // ?????
            //'itunes'      => array() .. // ?????
            'entries'     => array(),
        );

        $ii = 0;
        foreach ($entries as $entry) {

            $array['entries'][] = array(
                'title'			=> $entry->name,				// required
                'link'			=> 'http://me.example.com',		// required
                'description'	=> mb_substr($entry->data, 0, 100),	// required

                // array('guid') == feed('id') グローバルにユニークなら記事のURLでも良い。
                'guid'			=> $ii++,						// required "tag:example.jp,2010-08-24:samplerss:1234"
                //'guid'         => 'id of the article, if not given link value will used', //optional
                'content'		=> $entry->data,//can contain html, optional
                //'lastUpdate'   => 'timestamp of the publication date', // optional
                //'comments'     => 'comments page of the feed entry', // optional
                //'commentRss'   => 'the feed url of the associated comments', // optional
                //'source'       => array().. ????
                //'category'     => array().. ????
                //'enclosure'    => array().. ????
            );
        }
        Zend_Feed::importArray($array, 'atom')->send();
    }
}
