#!/bin/bash
# This configuration script is executed from project root so references to files
# must also be from project root

# MySql
echo "------------------------------------------------------------"
echo "Configuring php MySql..."
sudo mkdir -p /usr/etc/
sudo cp src/Mekit/Bundle/TestBundle/CI/Travis/.my.cnf /usr/etc/my.cnf
sudo service mysql restart
mysql -e "SET GLOBAL wait_timeout=300;"
mysql -e "SHOW VARIABLES LIKE 'max_allowed_packet';"
mysql -e "CREATE DATABASE mekit_test;" -uroot


# Php
echo "------------------------------------------------------------"
echo "Configuring php CLI..."
# sudo php5enmod -s cli opcache - there is no command by this name
phpenv config-add src/Mekit/Bundle/TestBundle/CI/Travis/travis.php.ini

# Composer
echo "------------------------------------------------------------"
echo "Configuring composer..."
mkdir -p ~/.composer
cp src/Mekit/Bundle/TestBundle/CI/Common/composer.config.json ~/.composer/config.json
composer selfupdate


# Create Test Environment
echo "------------------------------------------------------------"
echo "Setting up test environment..."
composer create-project mekit/crm-platform --no-interaction
rm -rf crm-platform/vendor/mekit
mv crm-platform/vendor ./
mv crm-platform/app ./
mv crm-platform/web ./
rm -rf crm-platform
composer dump-autoload --optimize

