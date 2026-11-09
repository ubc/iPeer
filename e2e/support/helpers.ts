import { expect, type Locator, type Page } from '@playwright/test';

/**
 * Seed accounts from app/config/sql/ipeer_samples_data.sql, named by the role
 * each one has. Every seed account has the same password.
 */
export const USERS = {
  superAdmin: 'root',
  admin: 'admin2',
  instructor1: 'instructor1',
  instructor2: 'instructor2',
  tutor: 'tutor2',
  student: 'redshirt0001',
} as const;

export const PASSWORD = 'ipeeripeer';

/** Logs a seed account in and waits for the page after the login form. */
export async function login(page: Page, username: string): Promise<void> {
  // Log out first, then load a fresh login form: the form that /logout renders
  // belongs to the session it has just ended.
  await page.goto('/logout');
  await page.goto('/');
  await page.getByLabel('Username').fill(username);
  await page.getByLabel('Password').fill(PASSWORD);
  await page.getByRole('button', { name: 'Login' }).click();
  await expect(page.locator('#navigationOuter')).toBeVisible();
}

/** Logs the current account out. */
export async function logout(page: Page): Promise<void> {
  await page.goto('/logout');
  await expect(page.getByRole('button', { name: 'Login' })).toBeVisible();
}

/** The main tab bar. Scope navigation checks to it. */
export function nav(page: Page): Locator {
  return page.locator('#navigationOuter');
}

/**
 * Checks the main tab bar holds exactly the given tabs. iPeer builds the bar
 * from the permissions of the account, so this is the role check.
 */
export async function expectTabs(page: Page, tabs: string[]): Promise<void> {
  const all = ['Home', 'Courses', 'Users', 'Evaluation', 'Admin'];
  for (const tab of all) {
    const link = nav(page).getByRole('link', { name: tab, exact: true });
    await expect(link, `tab ${tab}`).toHaveCount(tabs.includes(tab) ? 1 : 0);
  }
}

/** The green flash message. */
export function goodMessage(page: Page): Locator {
  return page.locator('div.good-message');
}

/** The flash message area, which also holds the error messages. */
export function flashMessage(page: Page): Locator {
  return page.locator('#flashMessage');
}

/** A form validation message. */
export function errorMessage(page: Page): Locator {
  return page.locator('.error-message');
}

/** The page title, which also holds the breadcrumb. */
export function pageTitle(page: Page): Locator {
  return page.locator('h1.title');
}

/**
 * The h3 headings of one section of the student or tutor home page. The page
 * repeats the same headings in each section, so a heading belongs to the
 * section whose h2 is the closest one before it.
 */
export function sectionHeadings(page: Page, section: string): Locator {
  return page.locator(
    `xpath=//h2[normalize-space()='${section}']/following-sibling::h3` +
      `[preceding-sibling::h2[1][normalize-space()='${section}']]`
  );
}

/** The table that belongs to a results heading of the group import results. */
export function resultsTable(page: Page, heading: string): Locator {
  return page.locator(
    `xpath=//h2[normalize-space()='${heading}']/following-sibling::table[1]`
  );
}

/** Reads the record id out of the current URL. */
export function idFromUrl(page: Page): string {
  const parts = new URL(page.url()).pathname.split('/');
  return parts[parts.length - 1];
}

