# iPeer End-to-End (E2E) Browser Tests

This directory contains the Playwright tests for iPeer. They replace the old Selenium tests.

The tests run in the `playwright` Docker Compose service. This service is part of the `test` profile. It connects to the `web-test` service on the `test` network. The tests use the seed data from `app/config/sql/ipeer_samples_data.sql`. The tests share one database, so they run one at a time. We do not think a parallel test suite is worth the work at this time.

## Run the Tests

```
./build/unit-integration-tests/prepare.sh
docker compose up -d playwright
docker compose exec playwright npm ci
docker compose exec playwright npm test
```

`npm test` checks the types first. To check the types alone, use
`docker compose exec playwright npm run typecheck`.

Playwright writes an HTML report to `e2e/playwright-report` after each run. If a test fails, it also writes a trace to `e2e/test-results`. In CI, the `e2e-tests` job uploads these directories as the `playwright-report` artifact when the job fails.

## The Test Database

Each test starts from a freshly loaded copy of the seed data. Before each test, the fixture in `support/test.ts` refills `ipeer_test` from `ipeer_seed`. See `support/db.ts` and `docker/db-test-init/e2e-seed.sql`. To reset the seed data, run `docker compose up -d --force-recreate --renew-anon-volumes db-test`.

Specs import `test` and `expect` from `support/test.ts`, not from `@playwright/test`. A test that imports them from `@playwright/test` might fail as the previous test's changes will be visible.

## Debug a Test

UI mode shows each step of a test, the page before and after the step, and the network traffic. It also runs a test again when you change it. The container serves it on port 9323, which docker-compose.yml publishes to localhost only:

```
docker compose exec playwright npx playwright test --ui --ui-host=0.0.0.0 --ui-port=9323
```

Then open `http://localhost:9323`.

There is no browser window to watch while a test runs, which is different from the Selenium tests. Playwright records the run, and you look through the record after it:

- The list of actions on the left fills in as the test runs. Click an action to see the page immediately before it and immediately after it.
- The strip of pictures along the top is the run from start to end. Move the pointer along it to go to a moment in the test.
- Point at the page in the snapshot to get a selector for an element. The Console and Network tabs show what the page did at that moment.

A recorded run is more useful than a window, because you can go back to a step and look again.

To work on one test, add part of its name:

```
docker compose exec playwright npx playwright test --ui --ui-host=0.0.0.0 --ui-port=9323 --grep "renames a group"
```

A file, or one test in a file by its line number, also works:

```
docker compose exec playwright npx playwright test tests/add-group.spec.ts
docker compose exec playwright npx playwright test tests/add-group.spec.ts:<line>
```
