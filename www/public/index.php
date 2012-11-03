<?php
require_once realpath(dirname(dirname(__FILE__))) . '/application/defines.inc';

require_once 'Zend/Application.php';
$application = new Zend_Application(
                        APPLICATION_ENV,
                        APP_ROOT . '/configs/application.ini'
                    );

$application->bootstrap()->run();
