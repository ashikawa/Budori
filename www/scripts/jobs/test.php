<?php
require_once dirname(dirname(__FILE__)) . '/defines.inc';

require_once 'Zend/Application.php';


$application = new Zend_Application('abstract', APP_ROOT . '/configs/script.ini');

$application->setBootstrap(ROOT . "/scripts/classes/TestScript.php", "TestScript");

$application->getAutoloader()
		->setFallbackAutoloader(true)
		->unregisterNamespace(array('Zend','ZendX'));


$application->run();
