<?php
/**
 * Foursquare OAuth2 ログインと、APIの呼び出し
 * @author shigeru.ashikawa
 */
class FoursquareController extends Neri_Controller_Action_Http
{
    /**
     * @var Zend_Session_Namespace
     */
    protected $_session = null;

    public function init()
    {
        parent::init();
        $this->_session = new Zend_Session_Namespace("FOURSQUARE_OAUTH");
    }

    public function indexAction()
    {
        $session = $this->_session;

        if ( !isset($session->accessToken) ) {
            return;
        }

        $client = new Zend_Http_Client();
        $client->setParameterGet("oauth_token", $session->accessToken);
        $client->setUri("https://api.foursquare.com/v2/users/self");

        $response = $client->request(Zend_Http_Client::GET);

        var_dump($response->getBody());
    }

    public function loginAction()
    {

        $url = "https://foursquare.com/oauth2/authenticate";

        $params = array(
            'client_id'		=> 'SHJXHH0V41KIZL0WDAK4PESCEE05QXXSTYGOCVLEMIO1GIA0',
            'redirect_uri'	=> 'http://budori.ashikawa.vm/foursquare/callback',
            'response_type'	=> 'code',
        );

        $url = $url . "?" . http_build_query($params);

        $this->_redirect($url);
    }

    public function callbackAction()
    {
        $params	= $this->_getAllParams();
        $code	= $params['code'];

        $client = new Zend_Http_Client();
        $client->setUri("https://foursquare.com/oauth2/access_token");

        $params = array(
            'client_id'		=> 'SHJXHH0V41KIZL0WDAK4PESCEE05QXXSTYGOCVLEMIO1GIA0',
            'client_secret'	=> 'LZJBR3GMFOHVPLZ2YBMINZROJCVDR0XRFQ0UQAZFXVPAWN3Z',
            'redirect_uri'	=> 'http://budori.ashikawa.vm/foursquare/callback',
            'grant_type'	=> 'authorization_code',
            'code'			=> $code,
        );

        $client->setParameterPost($params);

        $client->setHeaders("Accept", "application/json");

        $response	= $client->request(Zend_Http_Client::POST);

        $body		= json_decode($response->getBody());

        if (isset($body->error)) {
            throw new Zend_Service_Exception("OAuth callback error '{$body->error}'");
        }

        $this->_session->accessToken = $body->access_token;

        $this->_redirect("/foursquare/");
    }
}
