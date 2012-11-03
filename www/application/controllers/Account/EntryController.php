<?php
/**
 * アカウントの新規作成
 *　標準的な 入力チェック　→　insert の例
 */
class Account_EntryController extends Neri_Controller_Action_Http
{
    const ACCOUNT_ENTRY_STYLE_SHEET_PATH = '/style/content/account/entry.css';

    public function init()
    {
        $this->appendHeadLink(self::ACCOUNT_ENTRY_STYLE_SHEET_PATH);
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $this->appendPankuzu('アカウント作成', '/'.$this->getRequest()->getControllerName() );
        $this->prependTitle('アカウント作成');
    }

    public function indexAction()
    {
        $json = new Zend_Json();

        $this->view->assign(array(
            'json'	=> $json->encode( $this->getValidator() ),
        ));
    }

    public function confAction()
    {
        $this->appendPankuzu('確認');

        $params = $this->_getAllParams();

        $form = $this->getForm($params);

        if ( !$form->isValid() ) {

            $this->view->assign( 'messages', $form->getMessages() );

            return $this->_forward( 'index' );
        }

        $this->view->assign(
            'filtered', $form->getUnescaped()
        );

        $transaction = new Budori_TransactionToken();
        $transaction->saveToken();

        $this->view->assign('transaction',$transaction);
    }

    public function endAction()
    {
        $this->appendPankuzu('完了');

        if ( !Budori_TransactionToken::is() ) {
            return ;
        }

        $params = $this->_getAllParams();

        $db = Budori_Db::factory();

        $atters = array(
            'name'	=> $params['name'],
            'id'	=> $params['id'],
            'pass'	=> $params['pass1'],
        );

        $db->beginTransaction();

        $account = new Account($db);

        try {

            $ret = $account->create($atters);

        } catch ( Exception $e) {

            $db->rollBack();
            throw $e;
        }

        $db->commit();

        //登録後、ログイン処理
        $auth = new Neri_Auth_Adapter_Id($db);
        $auth->setIdentity($params['id']);

        Neri_Member::getInstance()->login($auth);
    }

    /**
     * Enter description here...
     * @param  unknown_type        $params
     * @return Budori_Filter_Input
     */
    protected function getForm( $params )
    {
        return new Budori_Filter_Input( $this->getFilter(), $this->getValidator(), $params );
    }

    protected function getFilter()
    {
        return Budori_Config::factory('form/sample.ini','filter')->toArray();
    }

    /**
     * Enter description here...
     * @return array
     */
    protected function getValidator()
    {
        return Budori_Config::factory('form/sample.ini','validator')->toArray();
    }
}
