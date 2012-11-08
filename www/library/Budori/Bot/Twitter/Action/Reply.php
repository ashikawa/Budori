<?php
require_once 'Budori/Bot/Twitter/Action/Interface.php';

class Budori_Bot_Twitter_Action_Reply implements Budori_Bot_Twitter_Action_Interface
{

    /**
     * @var callable
     */
    protected $_callback = null;

    /**
     * @var Zend_Service_Twitter
     */
    protected $_client   = null;

    /**
     * @var array
     */
    protected $_me      = null;

    /**
     * @param Zend_Service_Twitter $client
     */
    public function __construct(Zend_Service_Twitter $client = null)
    {
        $this->_client = $client;
    }

    /**
     * @return Zend_Service_Twitter
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @param  Zend_Service_Twitter            $client
     * @return Budori_Bot_Twitter_Action_Reply
     */
    public function setClient(Zend_Service_Twitter $client)
    {
        $this->_client;

        return $this;
    }

    /**
     * @param  callable                        $callback
     * @return Budori_Bot_Twitter_Action_Reply
     */
    public function setCallback($callback)
    {
        $this->_callback = $callback;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->_callback;
    }

    public function init()
    {
        $client    = $this->getClient();
        $this->_me = $client->account->verifyCredentials();
    }

    public function run($tweet)
    {
        $client  = $this->getClient();
        $me      = $this->_me;

        // メッセージが自分へのリプライなら無視する
        // Bot 同士の無限ループを避けるため
        if (isset($tweet['in_reply_to_user_id_str'])
                && ($tweet['in_reply_to_user_id_str'] == $me->id) ) {

            continue ;
        }

        // 自分のメッセージも無視する
        if (isset($tweet['from_user_id_str'])
                && $tweet['from_user_id_str'] == $me->id) {

            continue ;
        }

        $message = call_user_func_array($this->_callback, array($tweet));

        $response = $client->status->update($message, $tweet['id_str']);

        if ($response->error) {
            throw new Zend_Service_Exception($response->error);
        }
    }
}
