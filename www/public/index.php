<?php
/**
 * フレームワークが処理できなかった例外。
 * @param unknown_type $exception
 */
//function myExceptionHandler($exception)
//{
//	error_log($exception);
//	// redirect?
//	exit();
//}
//set_exception_handler('myExceptionHandler');


require_once realpath(dirname(dirname(__FILE__))) . '/application/defines.inc';

/**
 * Requestオブジェクトのparse_urlエラーの回避。
 * mixi OpenId対策。
 * ちゃんと url_encode くらいして欲しい。
 */
$requestPathInfo = explode('?', $_SERVER['REQUEST_URI']);
if(count($requestPathInfo) > 1){
	$_SERVER['REQUEST_URI'] = array_shift($requestPathInfo) . '?' . urlencode(implode('?',$requestPathInfo));
}

require_once 'Zend/Application.php';
$application = new Zend_Application(
						APPLICATION_ENV,
						APP_ROOT . '/configs/application.ini'
			        );

$application->bootstrap()->run();
