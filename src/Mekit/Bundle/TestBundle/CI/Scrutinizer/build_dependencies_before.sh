#!/bin/sh
# This configuration script is executed from project root so references to files
# must also be from project root

# Composer
echo "------------------------------------------------------------"
echo "Configuring composer..."
mkdir -p ~/.composer
cp src/Mekit/Bundle/TestBundle/CI/Common/composer.config.json ~/.composer/config.json

