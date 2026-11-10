import { expect, test, type Locator, type Page } from '@playwright/test';
import { PASSWORD } from './seed';

export async function login(page: Page, username: string): Promise<void> {
  await test.step(
    `Log in as ${username}`,
    async () => {
      // Log out first, then load a fresh login form: the form that /logout renders
      // belongs to the session it has just ended.
      await page.goto('/logout');
      await page.goto('/');
      await page.getByLabel('Username').fill(username);
      await page.getByLabel('Password').fill(PASSWORD);
      await page.getByRole('button', { name: 'Login' }).click();
      await expect(page.locator('#navigationOuter')).toBeVisible();
    },
    { box: true }
  );
}

export async function logout(page: Page): Promise<void> {
  await test.step(
    'Log out',
    async () => {
      await page.goto('/logout');
      await expect(page.getByRole('button', { name: 'Login' })).toBeVisible();
    },
    { box: true }
  );
}

export function nav(page: Page): Locator {
  return page.locator('#navigationOuter');
}

export async function expectTabs(page: Page, tabs: string[]): Promise<void> {
  await test.step(
    `Expect tabs ${tabs.join(', ')}`,
    async () => {
      // iPeer builds the tab bar from the permissions of the account, so the tabs
      // that are absent matter as much as the tabs that are present.
      const all = ['Home', 'Courses', 'Users', 'Evaluation', 'Admin'];
      for (const tab of all) {
        const link = nav(page).getByRole('link', { name: tab, exact: true });
        await expect(link, `tab ${tab}`).toHaveCount(tabs.includes(tab) ? 1 : 0);
      }
    },
    { box: true }
  );
}

export function goodMessage(page: Page): Locator {
  return page.locator('div.good-message');
}

export function flashMessage(page: Page): Locator {
  // Only the default flash element renders this id. A message set with the
  // 'good' key renders through elements/good.ctp instead, so use goodMessage().
  return page.locator('#flashMessage');
}

export function errorMessage(page: Page): Locator {
  return page.locator('.error-message');
}

export function pageTitle(page: Page): Locator {
  // Holds the breadcrumb as well as the title.
  return page.locator('h1.title');
}

export function sectionHeadings(page: Page, section: string): Locator {
  // The student and tutor home pages repeat the same h3 headings in each
  // section, so a heading belongs to the section whose h2 is closest before it.
  return page.locator(
    `xpath=//h2[normalize-space()='${section}']/following-sibling::h3` +
      `[preceding-sibling::h2[1][normalize-space()='${section}']]`
  );
}

export function resultsTable(page: Page, heading: string): Locator {
  return page.locator(`xpath=//h2[normalize-space()='${heading}']/following-sibling::table[1]`);
}

export function idFromUrl(page: Page): string {
  const parts = new URL(page.url()).pathname.split('/');
  return parts[parts.length - 1];
}
