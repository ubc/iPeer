import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests',
  // Loads the seed data once. support/test.ts then resets the database to it
  // before each test.
  globalSetup: './support/global-setup.ts',
  // The tests share one database, so they run one at a time.
  fullyParallel: false,
  workers: 1,
  forbidOnly: !!process.env.CI,
  reporter: [['list'], ['html', { open: 'never' }]],
  use: {
    baseURL: process.env.BASE_URL ?? 'http://web-test',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
  projects: [
    {
      // The whole suite. The browser is a setting of the project, not its subject.
      name: 'e2e',
      use: { ...devices['Desktop Chrome'] },
    },
  ],
});
