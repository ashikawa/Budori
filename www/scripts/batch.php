<?php
/**
 * php --define APPLICATION_ENV=development batch.php -c ... ?
 */
var_export($_SERVER);
define("APPLICATION_ENV", 'development');

require_once dirname( dirname(__FILE__) ) . "/application/defines.inc";

require_once 'Zend/Application.php';
$application = new Zend_Application(
						APPLICATION_ENV,
						APP_ROOT . '/configs/application.ini'
			        );

$application->bootstrap();

$frontController = $application->getBootstrap()->getResource('FrontController');

$frontController->resetInstance();

$frontController->throwExceptions(true)
	->setRequest( new Zend_Controller_Request_Simple() )
	->setResponse( new Zend_Controller_Response_Cli() )
	->setRouter( new Budori_Controller_Router_Cli() )
	->setControllerDirectory(dirname(__FILE__) . "/controller");


$application->run();

return 0;