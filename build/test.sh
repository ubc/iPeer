#!/bin/sh
# Load the seed data, then run the unit tests in the app container:
#   composer test                                      all tests
#   composer test -- case components/evaluation_component   one case
#   composer test -- group model                       one group
# The test database is ipeer_test unless IPEER_DB_NAME is set.
set -u
cd "$(dirname "$0")/.."

if [ $# -ne 0 ] && [ $# -ne 2 ]; then
    echo "Usage: composer test [-- <case|group> <name>]" >&2
    exit 2
fi

database=${IPEER_DB_NAME:-ipeer_test}
IPEER_DB_NAME=$database php build/test-db.php || exit 1

status=0

# run <app or plugin name> <case|group> <name>
run() {
    IPEER_DB_NAME=$database sh cake/console/cake -app app testsuite "$1" "$2" "$3" || {
        echo "Test $1 $2 $3 failed" >&2
        status=1
    }
}

if [ $# -eq 2 ]; then
    run app "$1" "$2"
else
    # App tests: the groups in app/tests/groups
    run app group component
    run app group model
    run app group caliper
    run app group libs
    run app group controller
    # Plugin tests: the cases in app/plugins/*/tests/cases
    run file_upload case behaviors/file_upload
    run file_upload case helpers/file_upload
    run file_upload case vendors/uploader
    run extended_test_case case libs/extended_test_case
fi

# the tests write cache files that the web server must be able to change
chmod 777 app/tmp
exit $status
