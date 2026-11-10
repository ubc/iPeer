import { expect, test } from '../support/test';
import { nav } from '../support/helpers';
import { FULL_NAMES, PASSWORD, USERS } from '../support/seed';

// This spec fills the login form itself, instead of the login() helper, because
// the login form is what it tests.
const USERNAME = USERS.superAdmin;

test('a user can log in, see the home page and log out', async ({ page }) => {
  await page.goto('/');

  await page.getByLabel('Username').fill(USERNAME);
  await page.getByLabel('Password').fill(PASSWORD);
  await page.getByRole('button', { name: 'Login' }).click();

  await expect(page.getByRole('heading', { level: 1, name: 'Home' })).toBeVisible();
  await expect(page.getByRole('heading', { level: 2, name: 'My Courses' })).toBeVisible();
  // Not just that somebody is signed in, but that it is the account that was
  // typed into the form.
  await expect(
    nav(page).getByRole('link', { name: FULL_NAMES.superAdmin, exact: true })
  ).toBeVisible();

  await page.getByRole('link', { name: 'Logout' }).click();

  await expect(page.getByRole('button', { name: 'Login' })).toBeVisible();

  // The session has ended, so the home page sends the user to the login form.
  await page.goto('/');
  await expect(page.getByRole('button', { name: 'Login' })).toBeVisible();
  await expect(page.getByRole('link', { name: 'Logout' })).toHaveCount(0);
});
