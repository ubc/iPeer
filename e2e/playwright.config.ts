import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests',
  // The tests share one seeded database, so they run one at a time.
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
    // The seed check runs first. If the seeded database is not what
    // support/seed.ts describes, the rest of the suite is skipped.
    {
      name: 'seed',
      testMatch: /seed\.setup\.ts/,
      use: { ...devices['Desktop Chrome'] },
    },
    {
      name: 'chromium',
      testIgnore: /seed\.setup\.ts/,
      use: { ...devices['Desktop Chrome'] },
      dependencies: ['seed'],
    },
  ],
});
