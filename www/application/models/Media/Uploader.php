<?php
/**
 * 画像ファイルのアップロードクラス
 *
 * 1. tmpファイル　アップロードの確認、
 * 2. アップロード後のバリデーション、
 * 3. データの登録
 * のメソッドで構成
 *
 * 複数のルールを切り替えるなら
 * 	1. setValidator() メソッドを作って、クラスの外から入力ルールを設定するようにする
 * 	2. _initValidator() をオーバーライドする(Media_UploaderAbstractとか作る)
 * のどちらか。　用件次第。
 */
class Media_Uploader
{
    /**
     * Enter description here...
     * @var Budori_Upload
     */
    protected $_upload;

    /**
     * Enter description here...
     * @var Zend_Validate
     */
    protected $_validator;

    /**
     * 入力チェック処理済フラグ
     * @var boolean
     */
    protected $_isValid = false;

    /**
     * アップロードを許可する mime type
     * @var array
     */
    protected $_arrowMime = array(
                        'image/jpeg',
                        'image/png',
                        'image/gif',
//						'image/pjpeg',
//						'image/tiff',
                    );
    /**
     * ファイルサイズの上限
     * 使用できる単位
     * 'B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'
     * @see Zend_Validate_File_Size::_toByteString()
     * @var string
     */
    protected $_maxFileSize = '500kB';

    /**
     * Enter description here...
     * @todo 引数の member はいらないかもしれぬ。
     *
     * @param Budori_Upload           $upload
     * @param Budori_Member_Interface $member
     */
    public function __construct(Budori_Upload $upload)
    {
        $this->_upload = $upload;

        $this->_initValidator();
    }

    /**
     * バリデータの初期化
     */
    protected function _initValidator()
    {
        $mimes = array_merge(
                array('magicfile' => getenv('MAGIC')),
                $this->_arrowMime
            );

        $validator = new Zend_Validate();

        $maxFileSize = $this->_maxFileSize;

        $validator->addValidator(new Zend_Validate_File_FilesSize(array('max'=>$maxFileSize)))
                        ->addValidator(new Zend_Validate_File_MimeType($mimes));

        $this->_validator = $validator;
    }

    /**
     * ファイルアップロード　エラーフラグ
     * UPLOAD_ERR_*
     * @return integer
     */
    public function getCode()
    {
        return $this->_upload->getCode();
    }

    /**
     * ファイルアップロード成功フラグ
     * @return boolean
     */
    public function isUploadSuccess()
    {
        return ( $this->_upload->getCode() == UPLOAD_ERR_OK );
    }

    /**
     * ファイルアップロード　エラーメッセージ
     * @return string
     */
    public function getUploadMessage()
    {
        return array($this->_upload->getMessage());
    }

    /**
     * ファイルチェック
     * @return boolean
     * @throws Budori_Exception
     * 	ファイルのアップロードが失敗している場合
     */
    public function isValid()
    {
        if (!$this->isUploadSuccess()) {
            throw new Budori_Exception('upload is not successed');
        }

        $upload = $this->_upload;

        $ret = $this->_validator->isValid($upload->getTmpFile()->getPathname());

        $this->_isValid = $ret;

        return $ret;
    }

    /**
     * ファイルチェック エラーメッセージ
     * @return array
     */
    public function getValidateMessage()
    {
        return $this->_validator->getMessages();
    }

    /**
     * アップロード後、保存処理
     *
     * @param  Zend_Db_Adapter_Abstract $db
     * @param  string                   $owner
     * @return integer
     */
    public function saveFile(Zend_Db_Adapter_Abstract $db, $owner )
    {
        if (!$this->_isValid) {
            throw new Budori_Exception('validatoer is not passed');
        }

        $upload = $this->_upload;

        $tmpFile = $upload->getTmpFile();

        $fileInfo = pathinfo($upload->getName());
        $contents = file_get_contents($tmpFile->getPathname());

        $data = array(
            'ext'	=> strtolower($fileInfo['extension']),
            'owner'	=> $owner,
            'name'	=> $upload->getName(),
            'mime'	=> $this->_getMimeBuffer($contents),
            'size'	=> $upload->getSize(),
            'data'	=> Media::encodeData($contents),
        );

        $mediaTable = new Neri_Db_Table_Media($db);

        return $mediaTable->insert($data);
    }

    /**
     * Mime タイプの取得
     * @param  string(binary) $contents
     * @return string
     */
    protected function _getMimeBuffer($contents)
    {
        $mime = new Budori_File_Mime();
        $mime->open();

        return $mime->buffer($contents);
    }
}
