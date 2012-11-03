<?php
/**
 * Google のライブラリ
 * @author shigeru.ashikawa
 */
class PlusController extends Neri_Controller_Action_Http
{

    const CLIENT_ID		= '682295594430.apps.googleusercontent.com';

    const CLIENT_SECRET	= 'IqgT_ZLr4CmmY3wW9Xn-2GgJ';

    const CALLBACK_URL	= 'http://budori.ashikawa.com/plus/callback';

    /**
     * @var apiClient
     */
    protected $_client	= null;

    /**
     * @var apiPlusService
     */
    protected $_plus	= null;

    /**
     * @var
     */
    protected $_session	= null;

    public function init()
    {
        parent::init();

        // for devel
        Zend_Session::setOptions(array("cookie_domain" => ".ashikawa.com"));

        require_once 'google-api-php-client/src/apiClient.php';

        $client = new apiClient();

        $client->setApplicationName("Google+ PHP Starter Application");
        $client->setClientId( self::CLIENT_ID );
        $client->setClientSecret( self::CLIENT_SECRET );
        $client->setRedirectUri( self::CALLBACK_URL );

        $this->_client = $client;

        $session = new Zend_Session_Namespace("GOOGLE_PLUS");

        if ( isset($session->OAUTH_TOKEN) ) {
            $client->setAccessToken($session->OAUTH_TOKEN);
        }
        $this->_session = $session;

        require_once 'google-api-php-client/src/contrib/apiPlusService.php';

        $plus = new apiPlusService($client);

        $this->_plus = $plus;
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $this->view->assign(array(
            "client"	=> $this->_client,
        ));
    }

    public function indexAction()
    {
        $client = $this->_client;

        if ( $client->getAccessToken() ) {

            $plus		= $this->_plus;
            $optParams	= array( 'maxResults' => 100 );

            $this->view->assign(array(
                "me"			=> $plus->people->get('me'),
                "activities"	=> $plus->activities->listActivities('me', 'public', $optParams),
            ));
        }
    }

    /**
     * https://accounts.google.com/o/oauth2/auth
     *		? response_type = code
     *		& redirect_uri = http%3A%2F%2Fbudori.ashikawa.com%2Fplus%2Fcallback
     *		& client_id = 682295594430.apps.googleusercontent.com
     *		& scope = https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fplus.me
     */
    public function authorizeAction()
    {
        $client	= $this->_client;

        $url	= $client->createAuthUrl();

        return $this->_redirect($url);
    }

    public function callbackAction()
    {
        $session	= $this->_session;
        $client		= $this->_client;

        $session->OAUTH_TOKEN = $client->authenticate();
        exit;

        return $this->_redirect("/plus/");
    }

    public function logoutAction()
    {
        $this->_session->unsetAll();

        return $this->_redirect("/plus/");
    }
}
