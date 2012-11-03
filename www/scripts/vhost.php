#!/usr/bin/php
<?php
require_once './defines.inc';

$domain				= "www.budori.local";
$application_env	= "development";
$port				= "80";

$include	= array(
        "C:\Program Files\PHP\lib\Zend\library",
        "C:\Program Files\PHP\lib\Smarty\libs",
        "C:\Program Files\PHP\lib\PEAR",
    );

$htdocs			= ROOT . DIRECTORY_SEPARATOR . "public";
$includeDirs	= '.' . PATH_SEPARATOR . implode(PATH_SEPARATOR, $include) . PATH_SEPARATOR;

$outputString = <<< DOC_END
<VirtualHost *:$port>
    ServerName $domain
    DocumentRoot "$htdocs"
    SetEnv APPLICATION_ENV $application_env
    # Other directives here

    <Directory "$htdocs">
        Options -Indexes FollowSymLinks
        order deny,allow
        deny from ALL
        allow from ALL
        AllowOverride All
    </Directory>

    <IfModule mod_php5.c>
        php_value include_path "$includeDirs"
    </IfModule>

</VirtualHost>
DOC_END;

echo $outputString;
