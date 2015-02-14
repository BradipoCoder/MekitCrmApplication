#!/bin/bash
# This configuration script is executed from project root so references to files
# must also be from project root

# MySql - fix "Mysql has gone away" problem
echo "------------------------------------------------------------"
echo "Configuring php MySql..."
mysql -e "SHOW VARIABLES LIKE 'max_allowed_packet';"
cat src/Mekit/Bundle/TestBundle/CI/Travis/.my.cnf >> /usr/etc/my.cnf
echo "------------------------------------------------------------"
cat /usr/etc/my.cnf
echo "------------------------------------------------------------"
sudo service mysql restart
mysql -e "SET GLOBAL wait_timeout=300;"
mysql -e "SHOW VARIABLES LIKE 'max_allowed_packet';"

# Php
echo "------------------------------------------------------------"
echo "Configuring php CLI..."
phpenv config-add src/Mekit/Bundle/TestBundle/CI/Travis/travis.php.ini


