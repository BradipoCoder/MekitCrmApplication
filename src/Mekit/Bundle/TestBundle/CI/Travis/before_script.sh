#!/bin/bash
# This configuration script is executed from project root so references to files
# must also be from project root

# MySql - fix "Mysql has gone away" problem
echo "\n\n------------------------------------------------------------"
echo "Configuring php MySql..."
mysql -e "SHOW VARIABLES LIKE 'max_allowed_packet';"
sudo mkdir -p /usr/etc/
sudo cp src/Mekit/Bundle/TestBundle/CI/Travis/.my.cnf /usr/etc/my.cnf
echo "------------------------------------------------------------"
cat /usr/etc/my.cnf
echo "------------------------------------------------------------"
sudo service mysql restart
mysql -e "SET GLOBAL wait_timeout=300;"
mysql -e "SHOW VARIABLES LIKE 'max_allowed_packet';"
mysql -e "CREATE DATABASE mekit_test;" -uroot

# Php
echo "\n\n------------------------------------------------------------"
echo "Configuring php CLI..."
phpenv config-add src/Mekit/Bundle/TestBundle/CI/Travis/travis.php.ini


# Composer
echo "\n\n------------------------------------------------------------"
echo "Configuring composer..."
mkdir -p ~/.composer
cp src/Mekit/Bundle/TestBundle/CI/Travis/composer.config.json ~/.composer/config.json
composer selfupdate

# Create Test Environment
echo "\n\n------------------------------------------------------------"
echo "Setting up test environment..."
composer create-project mekit/crm-platform --no-interaction
rm -rf crm-platform/vendor/mekit
mv crm-platform/vendor ./
mv crm-platform/app ./
mv crm-platform/web ./
rm -rf crm-platform
composer dump-autoload --optimize
mv app/config/parameters_test.yml.dist app/config/parameters_test.yml
sed -i -e 's/root/travis/g' app/config/parameters_test.yml
app/console oro:install --env=test --force --organization-name Mekit --user-name admin --user-password admin --user-email admin@example.com --user-firstname Mekit --user-lastname Tester --sample-data n --application-url http://localhost/
app/console doctrine:fixture:load --env=test --no-debug --append --no-interaction --fixtures ./vendor/oro/platform/src/Oro/Bundle/TestFrameworkBundle/Fixtures

