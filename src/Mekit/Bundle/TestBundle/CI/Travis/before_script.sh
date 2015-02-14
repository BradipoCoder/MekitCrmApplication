#!/bin/bash
# This configuration script is executed from project root so references to files
# must also be from project root

echo "Configuring php MySql..."
mysql -e "SHOW VARIABLES LIKE 'max_allowed_packet';"
cp src/Mekit/Bundle/TestBundle/CI/Travis/.my.cnf ~/
sudo service mysql restart
mysql -e "SET GLOBAL wait_timeout=300;"


echo "Configuring php CLI..."
phpenv config-add src/Mekit/Bundle/TestBundle/CI/Travis/travis.php.ini

echo "------------------------------------------------------------"
php -i
