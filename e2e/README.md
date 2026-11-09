# iPeer browser tests

This directory contains the Playwright tests for iPeer. They are ported from the old previous Selenium tests.

The tests run in the `playwright` Docker Compose service. This service is part of the `test` profile. It connects to the `web-test` service on the `test` network. The tests use the seed accounts from `app/config/sql/ipeer_samples_data.sql`. The tests share one database, so they run one at a time (a limitation kept while porting from the Selenium tests - at this time we don't feel it's worth investing in a parallelizable test suite).

The tests do not cover surveys, because the feature is not in use.

## Run the tests

```
./build/unit-integration-tests/prepare.sh
docker compose up -d playwright
docker compose exec playwright npm ci
docker compose exec app-test vendor/bin/phing init-test-db
docker compose exec playwright npx playwright test
```

The tests change data in the test database. Run `init-test-db` again before each run. It replaces all data in the test database with the seed data.

Playwright writes an HTML report to `e2e/playwright-report` after each run. If a test fails, it also writes a trace to `e2e/test-results`. In CI, the `e2e-tests` job uploads these directories as the `playwright-report` artifact when the job fails.
