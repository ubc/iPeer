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

## Test Data

A test whose subject is not a form creates its starting data with SQL, through the helpers in `support/insert.ts`, because filling the forms is slow. The insert must write the rows the way the app saves them. Each type that a spec inserts has exactly one self-check test, which inserts the data and checks it with the same expect helpers as the test of the form. The test of the form stays on the form.

## Assertions

The flash messages that the specs check are in `support/messages.ts`, so a change of wording only needs one edit. Most checks compare the whole message. Messages that name test data, such as the row of a failed import, stay next to that data in the spec.

The line breaks and spaces of a page, a table cell, or a PDF depend on the markup and the styles of the document. `support/text.ts` has checks that do not depend on them:

- `expectTextIgnoringSpace(locator, expected)` compares the whole text. Each run of whitespace counts as one space. Use it when you know the whole text.
- `expectTextInOrder(locator, parts)` and `readInOrder(text)` check parts in order and ignore all whitespace. Each part must start at most 60 characters after the end of the part before it. Use them when other text, such as labels, comes between the parts, for example on a PDF page.

`pdfPages(content)` in `support/pdf.ts` gives the text of each page of a PDF export. It uses `unpdf`, which has no native system dependencies.

## Selectors

Find elements by role and accessible name, as a user finds them. If a view gives no good name, change the view, not the test. For example, link a table to its heading with `aria-labelledby`. Then find the table with `labelledTable(page, heading)`.

In new code, do not find an element by its position, such as `.nth()`, or by XPath. Some older helpers still do. Replace them when their views get names.

## Writing Tests

- **Log in:** use `login(page, username)` in `support/helpers.ts`. It posts the login form without an initial form load and returns the landing URL. The test then opens the page it needs by its URL. Only `login.spec.ts` tests the login form and logout. Tests should not share a session, because a session can keep old permissions.
- **Assertion Helpers:** wrap them in `test.step(..., { box: true })`, so a failure points to the line in the test, not into the helper.
- **Absence:** before a check that something is not on a page, check something that shows the page is loaded. Otherwise, the check can pass on a page that is still loading. For lists, use `waitForAjaxList(page)` in `support/ajax-list.ts`.
- **AJAX before a submit:** wait for the page's own requests, for example with `waitForAjaxList(page)` or `page.waitForResponse()`, before you submit a form. A page load can cancel a request that is still running, and Chromium then gives no response and no failure event.
- **Delete:** use the list menu, or `requestDelete(page, controller, id)` in `support/evaltools.ts`.
- **Date fields:** click the field first and wait for `#ui-datepicker-div`. The time picker writes the current date into an empty field when it opens.
- **New checks:** make sure that a new test fails when you remove the code that it checks.

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

Normally, a snapshot keeps the scroll position of the page at that step, and checks such as `expect()` do not scroll to the relevant DOM element. To change it so the DOM element is in view, `npm ci` patches the trace viewer to scroll to it via `scripts/trace-viewer-scroll.mjs`.

To work on one test, add part of its name:

```
docker compose exec playwright npx playwright test --ui --ui-host=0.0.0.0 --ui-port=9323 --grep "renames a group"
```

A file, or one test in a file by its line number, also works:

```
docker compose exec playwright npx playwright test tests/add-group.spec.ts
docker compose exec playwright npx playwright test tests/add-group.spec.ts:<line>
```

## Check for Flaky Tests

Run a new or changed test several times before you merge it:

```
docker compose exec playwright npx playwright test tests/add-group.spec.ts --repeat-each=5 --reporter=dot
```

To see how long each test takes, write a JSON report:

```
docker compose exec -e PLAYWRIGHT_JSON_OUTPUT_NAME=timings.json playwright npx playwright test --reporter=line,json
```
