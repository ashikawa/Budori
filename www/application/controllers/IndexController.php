<?php
/**
 * インデックストップ
 */
class IndexController extends Neri_Controller_Action_Http
{

    public function init()
    {
        parent::init();
        $this->setNoController();
    }

    public function indexAction()
    {}

    /**
     * /robots.txt
     * @see router.ini -> index_robots ...
     */
    public function robotsTxtAction()
    {
        $this->disableLayout();
    }
}
