<?php
class MemcacheController extends Neri_Controller_Action_Http
{
    /**
     * @var Memcached
     */
    protected $_memcached = null;

    public function init()
    {
        parent::init();
        $this->_initMemcache();
    }

    protected function _initMemcache()
    {
        $memcache = new Memcached();
        $memcache->addServer("localhost", 11211);

        $this->_memcached = $memcache;
    }

    public function indexAction()
    {
        $memcache = $this->_memcached;

        for ($i = 0; $i < 100; $i++) {
            $ret = $memcache->set("mykey" . $i, "myvalue". $i);
        }
        var_dump($ret);
    }

    public function dumpAction()
    {
        $class = new ReflectionClass('Memcached');
        var_dump($class->getConstants());
        exit;
    }
}
