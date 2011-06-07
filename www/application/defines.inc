<?php
if( !defined('APPLICATION_ENV')){
	define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
}

define( 'ROOT',realpath(dirname(dirname(__FILE__))) );
define( 'APP_ROOT', ROOT . '/application' );
define( 'CACHE_DIR', ROOT . '/data/cache' );
