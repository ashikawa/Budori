#!/bin/sh

echo "" > output.txt

alias testlog="tee -a output.txt"

# controlelr test
php phpunit.php modules/* 2>&1 | testlog
php phpunit.php controller/* 2>&1 | testlog
php phpunit.php models/* 2>&1 | testlog

