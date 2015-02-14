#!/bin/bash

echo "Configuring php CLI..."
phpenv config-add src/Mekit/Bundle/TestBundle/CI/Travis/travis.php.ini

echo "------------------------------------------------------------"
php -i
