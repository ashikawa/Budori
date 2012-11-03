<?php
/**
 * Github OAuth2 ログインと、APIの呼び出し
 * @author shigeru.ashikawa
 */
class GithubController extends Neri_Controller_Action_Http
{
    /**
     * @var Zend_Session_Namespace
     */
    protected $_session = null;

    public function init()
    {
        parent::init();
        $this->_session = new Zend_Session_Namespace("GITHUB_OAUTH");
    }

    public function indexAction()
    {
        $session = $this->_session;

        if ( !isset($session->accessToken) ) {
            return;
        }

//		var_dump($session->accessToken);exit;

        $client = new Zend_Http_Client();
        $client->setParameterGet("access_token", $session->accessToken);
        $client->setUri("https://api.github.com/user");

        $response = $client->request(Zend_Http_Client::GET);

        var_dump($response->getBody());
    }

    public function loginAction()
    {

        $url = "https://github.com/login/oauth/authorize";

        $scopes = array('user', 'public_repo', 'repo', 'delete_repo', 'gist');

        $params = array(
            'client_id'		=> '4feb7052be9c83382240',
            'redirect_uri'	=> 'http://budori.ashikawa.vm/github/callback',
            'scope'			=> implode(',', $scopes),
//			'state'			=> for CSRF
//			b594c43f2facb4d8d00e4ccd915c3136495ecf36
        );

        $url = $url . "?" . http_build_query($params);

        $this->_redirect($url);
    }

    public function callbackAction()
    {
//		var_dump($this->_getAllParams());

        $params	= $this->_getAllParams();
        $code	= $params['code'];

        $client = new Zend_Http_Client();
        $client->setUri("https://github.com/login/oauth/access_token");

        $params = array(
            'client_id'		=> '4feb7052be9c83382240',
            'redirect_uri'	=> 'http://budori.ashikawa.vm/github/callback',
            'client_secret'	=> 'b594c43f2facb4d8d00e4ccd915c3136495ecf36',
            'code'			=> $code,
//			'state'
        );

        $client->setParameterPost($params);

        $client->setHeaders("Accept", "application/json");

        $response = $client->request(Zend_Http_Client::POST);

        $body = json_decode($response->getBody());

        // $body->token_type	=> "bearer"
        // $body->access_token	=> string(40) "5f7c5e3127d8bab70db57ec25721440ddea80539"

        $this->_session->accessToken = $body->access_token;

        $this->_redirect("/github/");
    }
}
