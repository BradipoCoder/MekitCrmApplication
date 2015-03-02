#!/bin/bash
# Executed from project root so file references are also from project root

# This variable will hold the return value with which we will exit from this script (by default 128 === being pessimistic)
RETURNVAL="128"


# Run unit tests with coverage
echo "------------------------------------------------------------"
echo "Running unit tests..."
phpunit -c phpunit.xml --coverage-clover=coverage.clover.unit
RETURNVAL="$?"


# Upload test coverage data to Scrutinizer in any case - we do this before functional tests begin so Scrutinizer can proceed
echo "------------------------------------------------------------"
echo "Uploading coverage data to Scrutinizer..."
wget https://scrutinizer-ci.com/ocular.phar
php ocular.phar code-coverage:upload --format=php-clover coverage.clover.unit


# We will only be running functional tests if unit tests pass
if [ ${RETURNVAL} -eq "0" ] ; then

    # Install application for functional tests
    echo "------------------------------------------------------------"
    echo "Installing application..."
    mv app/config/parameters_test.yml.dist app/config/parameters_test.yml
    sed -i -e 's/root/travis/g' app/config/parameters_test.yml
    app/console oro:install --env=test --force --organization-name Mekit --user-name admin --user-password admin --user-email admin@example.com --user-firstname Mekit --user-lastname Tester --sample-data n --application-url http://localhost/
    app/console doctrine:fixture:load --env=test --no-debug --append --no-interaction --fixtures ./vendor/oro/platform/src/Oro/Bundle/TestFrameworkBundle/Fixtures


    # Run functional tests
    echo "------------------------------------------------------------"
    echo "Running functional tests..."
    phpunit -c phpunit_functional.xml
    RETURNVAL="$?"
else
    echo "Unit tests failed! Skipping functional tests!"
fi


# Exiting with RETURNVAL
exit ${RETURNVAL}
