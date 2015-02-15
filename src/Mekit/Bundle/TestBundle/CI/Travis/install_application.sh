#!/bin/bash
# This configuration script is executed from project root so references to files
# must also be from project root

# Install application for functional tests
echo "------------------------------------------------------------"
echo "Installing application..."
mv app/config/parameters_test.yml.dist app/config/parameters_test.yml
sed -i -e 's/root/travis/g' app/config/parameters_test.yml
app/console oro:install --env=test --force --organization-name Mekit --user-name admin --user-password admin --user-email admin@example.com --user-firstname Mekit --user-lastname Tester --sample-data n --application-url http://localhost/
app/console doctrine:fixture:load --env=test --no-debug --append --no-interaction --fixtures ./vendor/oro/platform/src/Oro/Bundle/TestFrameworkBundle/Fixtures
