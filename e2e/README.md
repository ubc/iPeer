# iPeer End-t-End (E2E) Browser Tests

This directory contains the Playwright tests for iPeer. They are ported from the old previous Selenium tests.

The tests run in the `playwright` Docker Compose service. This service is part of the `test` profile. It connects to the `web-test` service on the `test` network. The tests use the seed accounts from `app/config/sql/ipeer_samples_data.sql`. The tests share one database, so they run one at a time (a limitation kept while porting from the Selenium tests - at this time we don't feel it's worth investing in a parallelizable test suite).

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

## Debugging a Test

UI mode shows each step of a test, the page before and after the step, and the
network traffic. It also reruns a test when you change it. The container serves
it on port 9323, which docker-compose.yml publishes to localhost only:

```
docker compose exec app-test vendor/bin/phing init-test-db
docker compose exec playwright npx playwright test --ui --ui-host=0.0.0.0 --ui-port=9323
```

Then open `http://localhost:9323`.

There is no browser window to watch while a test runs, which is different from
the Selenium tests. Playwright records the run, and you look through the record
afterwards:

- The list of actions on the left fills in as the test runs. Click an action to
  see the page immediately before it and immediately after it.
- The strip of pictures along the top is the run from start to end. Move the
  pointer along it to go to a moment in the test.
- Point at the page in the snapshot to get a selector for an element. The
  Console and Network tabs show what the page did at that moment.

A recorded run is more useful than a window, because you can go back to a step
and look again.

To work on one test, add part of its name:

```
docker compose exec playwright npx playwright test --ui --ui-host=0.0.0.0 --ui-port=9323 --grep "renames a group"
```

A file, or one test in a file by its line number, also works:

```
docker compose exec playwright npx playwright test --ui --ui-host=0.0.0.0 --ui-port=9323 tests/merge-users.spec.ts
docker compose exec playwright npx playwright test tests/add-group.spec.ts:117
```

The seed check runs before whatever you picked, because the `main` project
depends on the `verify-seed` project. That is why a filter for one test reports
more tests than you asked for.

UI mode does not seed the database between runs. The tests change data, so run
`init-test-db` again when a test that passed starts to fail.

