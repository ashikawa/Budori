#!/bin/bash

php ./vhost.php > http-budori.conf
chmod 777 ../data/log/
chmod 777 ../data/cache/
chmod 777 ../data/cache/smarty_cache/
chmod 777 ../data/cache/temp/