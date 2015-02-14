#!/bin/sh

echo "------------------------------------------------------------"
php -i

echo "Configuring php CLI..."
phpenv config-add travis.php.ini

echo "------------------------------------------------------------"
php -i
