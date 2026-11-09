import { expect, test } from '@playwright/test';

// Seed account from app/config/sql/ipeer_samples_data.sql.
const USERNAME = 'root';
const PASSWORD = 'ipeeripeer';

test('a user can log in, see the home page and log out', async ({ page }) => {
  await page.goto('/');

  await page.getByLabel('Username').fill(USERNAME);
  await page.getByLabel('Password').fill(PASSWORD);
  await page.getByRole('button', { name: 'Login' }).click();

  await expect(page.getByRole('heading', { level: 1, name: 'Home' })).toBeVisible();
  await expect(page.getByRole('heading', { level: 2, name: 'My Courses' })).toBeVisible();

  await page.getByRole('link', { name: 'Logout' }).click();

  await expect(page.getByRole('button', { name: 'Login' })).toBeVisible();

  // The session has ended, so the home page sends the user to the login form.
  await page.goto('/');
  await expect(page.getByRole('button', { name: 'Login' })).toBeVisible();
  await expect(page.getByRole('link', { name: 'Logout' })).toHaveCount(0);
});
