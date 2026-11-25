#!/bin/sh
# Load the seed data, then run the unit tests in the app container:
#   composer test                                      all five test groups
#   composer test -- case components/evaluation_component   one case
#   composer test -- group model                       one group
# The test database is ipeer_test unless IPEER_DB_NAME is set.
set -u
cd "$(dirname "$0")/.."

if [ $# -ne 0 ] && [ $# -ne 2 ]; then
    echo "Usage: composer test [-- <case|group> <name>]" >&2
    exit 2
fi

php build/test-db.php || exit 1

database=${IPEER_DB_NAME:-ipeer_test}
status=0

run() {
    IPEER_DB_NAME=$database sh cake/console/cake -app app testsuite app "$1" "$2" || {
        echo "Test $1 $2 failed" >&2
        status=1
    }
}

if [ $# -eq 2 ]; then
    run "$1" "$2"
else
    # every group runs, also after a failed one
    for group in component model caliper libs controller; do
        run group "$group"
    done
fi

# the tests write cache files that the web server must be able to change
chmod 777 app/tmp
exit $status
