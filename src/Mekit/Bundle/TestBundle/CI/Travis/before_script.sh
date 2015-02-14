#!/bin/bash
# This configuration script is executed from project root so references to files
# must also be from project root

echo "Configuring php MySql..."
ls -la /etc/mysql/conf.d
/usr/sbin/mysqld --verbose --help | grep -A 1 "Default options"


echo "Configuring php CLI..."
phpenv config-add src/Mekit/Bundle/TestBundle/CI/Travis/travis.php.ini

echo "------------------------------------------------------------"
php -i
