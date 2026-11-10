import { test as base, expect, type Request } from '@playwright/test';
import { existsSync, writeFileSync } from 'fs';
import { openTestDatabase, resetToSeed, type TestDatabase } from './db';

// After a failed reset the data is in an unknown state, so the tests that
// follow would fail for reasons that have nothing to do with them. Playwright
// cannot stop a run from inside a test, so a failed reset writes a marker file,
// and every later test of the run skips when it finds it. Global setup chooses
// the path of the file.
function resetFailedEarlier(): boolean {
  const marker = process.env.E2E_RESET_FAILED_MARKER;
  return !!marker && existsSync(marker);
}

function recordResetFailure(error: unknown): void {
  const marker = process.env.E2E_RESET_FAILED_MARKER;
  if (marker) {
    writeFileSync(marker, String(error));
  }
}

// Specs import test and expect from here, not from @playwright/test, so that
// every test starts from the seed data.
export const test = base.extend<
  { seededDatabase: void; settledRequests: void },
  { testDatabase: TestDatabase }
>({
  testDatabase: [
    async ({}, use) => {
      const database = await openTestDatabase();
      await use(database);
      await database.connection.end();
    },
    { scope: 'worker' },
  ],

  seededDatabase: [
    async ({ testDatabase }, use, testInfo) => {
      if (resetFailedEarlier()) {
        // Ends the test here, as skipped.
        testInfo.skip(true, 'The database reset failed in an earlier test.');
      }
      try {
        await resetToSeed(testDatabase);
      } catch (error) {
        recordResetFailure(error);
        throw error;
      }
      await use();
    },
    { auto: true },
  ],

  // A test can end while the server still handles a request the page sent, for
  // example the save after a last click. Wait until the server has answered
  // every such request, so that the next reset does not race with its write.
  settledRequests: [
    async ({ page, baseURL }, use) => {
      // Only page loads and AJAX calls to iPeer can write data. Other requests
      // are left out: Playwright sends no end event for a font or a script
      // that a navigation interrupted, so they could look open forever.
      const appOrigin = new URL(baseURL ?? 'http://web-test').origin;
      const canWrite = (request: Request) =>
        new URL(request.url()).origin === appOrigin &&
        ['document', 'xhr', 'fetch'].includes(request.resourceType());

      const open = new Set<Request>();
      page.on('request', (request) => {
        if (canWrite(request)) {
          open.add(request);
        }
      });
      page.on('requestfinished', (request) => open.delete(request));
      page.on('requestfailed', (request) => open.delete(request));
      await use();
      await expect
        .poll(() => [...open].map((request) => request.url()), {
          message: 'requests to iPeer still open after the test',
          timeout: 5_000,
        })
        .toEqual([]);
    },
    { auto: true },
  ],
});

export { expect } from '@playwright/test';
export type { Locator, Page } from '@playwright/test';
