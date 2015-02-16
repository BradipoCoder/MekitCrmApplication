#!/bin/bash
# This configuration script is executed from project root so references to files
# must also be from project root

# Upload test coverage data to scrutinizer
echo "------------------------------------------------------------"
echo "Uploading coverage data to Scrutinizer..."
wget https://scrutinizer-ci.com/ocular.phar
php ocular.phar code-coverage:upload --format=php-clover coverage.clover.unit
