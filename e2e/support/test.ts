import { test as base, expect } from '@playwright/test';
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
  // every request, so that the next reset does not race with its write.
  settledRequests: [
    async ({ page }, use) => {
      let pending = 0;
      page.on('request', () => pending++);
      page.on('requestfinished', () => pending--);
      page.on('requestfailed', () => pending--);
      await use();
      await expect
        .poll(() => pending, { message: 'requests still open after the test', timeout: 5_000 })
        .toBe(0);
    },
    { auto: true },
  ],
});

export { expect } from '@playwright/test';
export type { Locator, Page } from '@playwright/test';
