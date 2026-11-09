# iPeer browser tests

This directory contains the Playwright tests for iPeer. They are ported from the old previous Selenium tests.

The tests run in the `playwright` Docker Compose service. This service is part of the `test` profile. It connects to the `web-test` service on the `test` network. The tests use the seed accounts from `app/config/sql/ipeer_samples_data.sql`. The tests share one database, so they run one at a time (a limitation kept while porting from the Selenium tests - at this time we don't feel it's worth investing in a parallelizable test suite).

The tests do not cover surveys, because the feature is not in use. They do not cover the Canvas integration, because it needs an external service and account credentials.

`support/seed.ts` holds the accounts, courses and students that `app/config/sql/ipeer_samples_data.sql` creates. `tests/seed.setup.ts` checks those facts before the rest of the suite runs, so a change to the seed file fails there and not in many specs. `support/helpers.ts` holds the steps that every spec uses, such as login and the page locators, and `support/groups.ts` and `support/courses.ts` hold the steps for those areas. `fixtures/` holds the CSV files that the import tests upload.

The tests assert roles, headings and links instead of exact counts of seeded data, so that more seed data does not break them.

## Run the tests

```
./build/unit-integration-tests/prepare.sh
docker compose up -d playwright
docker compose exec playwright npm ci
docker compose exec app-test vendor/bin/phing init-test-db
docker compose exec playwright npm test
```

`npm test` checks the types first. To check the types alone, use
`docker compose exec playwright npm run typecheck`.

The tests change data in the test database. Run `init-test-db` again before each run. It replaces all data in the test database with the seed data.

Playwright writes an HTML report to `e2e/playwright-report` after each run. If a test fails, it also writes a trace to `e2e/test-results`. In CI, the `e2e-tests` job uploads these directories as the `playwright-report` artifact when the job fails.
