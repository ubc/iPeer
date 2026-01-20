#!/bin/bash
set -euo pipefail

cd "$(dirname "$0")/../.." # run in repo root

mkdir -p app/tmp/cache/persistent app/tmp/cache/models app/tmp/logs
chmod -R 777 app/tmp
echo "Starting containers"
docker compose build app-test
docker compose up -d db-test app-test web-test
docker compose exec app-test composer install # due to docker volume mounts, the vendor folder is overwritten, so we reinstall all dependencies

echo "Waiting for containers to be ready..."
for i in {1..10}; do
    if docker compose exec app-test php -r "echo @file_get_contents('http://web-test/') !== false ? 'ok' : 'fail';" 2>/dev/null | grep -q "ok"; then
        echo "web-test is responding to HTTP requests"
        break
    fi
    echo "Waiting for web-test HTTP... ($i/10)"
    sleep 1
done

# logs for debugging
echo "=== container status ==="
docker compose ps
echo "=== web-test logs ==="
docker compose logs web-test
echo "=== app-test logs ==="
docker compose logs app-test
