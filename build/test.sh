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
    # every group runs, also after a failed one
    for group in component model caliper libs controller; do
        run app group "$group"
    done
    # the only plugin tests that run on PHP 8; the other plugin test files
    # have not been updated
    run file_upload case helpers/file_upload
fi

# the tests write cache files that the web server must be able to change
chmod 777 app/tmp
exit $status
