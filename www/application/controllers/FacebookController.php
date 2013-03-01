<?php
/**
 * @author shigeru.ashikawa
 */
class FacebookController extends Neri_Controller_Action_Http
{
    public function init()
    {
        parent::init();

        if (!$this->_helper->hasHelper("facebook")) {
        	$this->_helper->addHelper(new Budori_Controller_Action_Helper_Facebook());
        }
    }
    
    /**
     * @return Budori_Controller_Action_Helper_Facebook
     */
    protected function _getFacebookHelper()
    {
		return $this->_helper->getHelper("facebook");
    }

    public function indexAction()
    {
    	$helper = $this->_getFacebookHelper();
        $user   = $helper->getClient()->getUser();

        var_export($user);
    }

    public function friendsAction()
    {
        $this->disableLayout();
        $this->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "text/plain");

        $facebook = $this->_getFacebookHelper()->getClient();

        var_dump($facebook->api("/me/friends"));
    }

    /**
     * call oauth and redirect
     */
    public function authorizeAction()
    {
		$helper = $this->_getFacebookHelper();
		$helper->authorize("/facebook/callback");
		return;
    }

    public function callbackAction()
    {
    	$helper = $this->_getFacebookHelper();
    	$helper->callback();

		$this->_forward("index");
    }

    /**
     * post message for wall
     */
    public function postAction()
    {
        $client		= $this->_getFacebookHelper()->getClient();
        
        if ( !$client->getUser() || ! $this->_getParam("value") ) {
        	return ;
        }

        $message	= $this->_getParam("value");

        // 改行コードを CRLF に統一
        $message	= str_replace(array("\x0d\x0a", "\x0a", "\x0d"), "\x0d\x0a", $message);

        $options = array(
            "picture"		=> "http://example.com/hoge.gif",
            "link"			=> "http://example.com/",
            "name"			=> "application name",
            "caption"		=> "short caption",
            "description"	=> "long desctiption",
            "source"		=> "http://example.com/",
            "actions"		=> array(
                array("name" => "action(optional)", "link" => "action link(optional)"),
            ),
            "message"		=> $message,
        );

        try {

            $result = $client->api('/me/feed', 'post', $options);

        } catch (Exception $e) {
            $this->_logout();
            throw $e;
        }
    }

    public function albumAction()
    {
        $facebook = $this->_getFacebookHelper()->getClient();
        $facebook->setFileUploadSupport(true);

        $options = array(
            "name"			=> "penguin (album)",
            "description"	=> "album description!!!!!",
        );

        $albumResult	= $facebook->api("/me/albums", "POST", $options);
        $albumId		= $albumResult['id'];

        $photoResult = array();

        $image	= realpath(ROOT . "/data/img/20.jpg");
        $options = array(
            "image"		=> '@' . $image,
//			"message"	=> "Photo Message 20",
        );

        $photoResult[] = $facebook->api("/$albumId/photos", "POST", $options);

        $this->view->assign(array(
            "album"	=> $albumResult,
            "photo"	=> $photoResult,
        ));
    }

    public function logoutAction()
    {
        $this->_logout();
        $this->_redirect("/facebook/");
    }

    /**
     * remove oauth session
     */
    protected function _logout()
    {
        $this->_getFacebookHelper()->getClient()->destroySession();
    }

    public function tabAction()
    {
        $this->setNoRender();
        $this->disableLayout();

        $facebook = $this->_getFacebookHelper()->getClient();
        var_export( $facebook->getSignedRequest() );
    }
}
