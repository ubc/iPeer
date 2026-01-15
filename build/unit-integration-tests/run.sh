#!/bin/bash
set -euo pipefail

cd "$(dirname "$0")/../.." # run in repo root

echo "Starting tests"
if [ $# -eq 0 ]; then
    docker compose exec app-test vendor/bin/phing test
elif [ $# -eq 2 ]; then
    docker compose exec app-test vendor/bin/phing test-single -Dtest.type="$1" -Dtest.name="$2"
else
    echo "Usage: $0 [<type> <name>]"
    echo "  No arguments: run all tests"
    echo "  <type> <name>: run specific test suite. Examples: "
    echo "      - case components/evaluation_component"
    echo "      - group model"
    exit 1
fi

echo "Tests complete."
docker compose down
