import { test as base } from '@playwright/test';
import { openTestDatabase, resetToSeed, type TestDatabase } from './db';

// Specs import test and expect from here, not from @playwright/test, so that
// every test starts from the seed data.
export const test = base.extend<{ seededDatabase: void }, { testDatabase: TestDatabase }>({
  testDatabase: [
    async ({}, use) => {
      const database = await openTestDatabase();
      await use(database);
      await database.connection.end();
    },
    { scope: 'worker' },
  ],
  seededDatabase: [
    async ({ testDatabase }, use) => {
      await resetToSeed(testDatabase);
      await use();
    },
    { auto: true },
  ],
});

export { expect } from '@playwright/test';
export type { Locator, Page } from '@playwright/test';
