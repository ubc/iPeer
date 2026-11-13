import { expect, test, type Dialog, type Locator, type Page } from '@playwright/test';
import path from 'path';
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

export async function expectAccessDenied(page: Page): Promise<void> {
  await test.step(
    'Expect access denied',
    async () => {
      // A refused page sends the visitor to the home page with a message that
      // names no permission, so the message and the landing page are all there
      // is to tell a refusal from a working page.
      await expect(flashMessage(page)).toContainText(
        'Access to this page is limited to authorized users'
      );
      await expect(page).toHaveURL(/\/home$/);
    },
    { box: true }
  );
}

// Runs `act`, which must make the page ask `question` with confirm(). Only that
// question is accepted: any other dialog is dismissed, so an unexpected
// confirmation cannot let a destructive action through. Fails when the
// question does not come.
export async function withConfirmation(
  page: Page,
  question: string,
  act: () => Promise<void>
): Promise<void> {
  const asked: string[] = [];
  const handler = (dialog: Dialog) => {
    asked.push(dialog.message());
    void (dialog.message() === question ? dialog.accept() : dialog.dismiss());
  };
  page.on('dialog', handler);
  try {
    await act();
  } finally {
    page.off('dialog', handler);
  }
  expect(asked, 'confirmation questions').toEqual([question]);
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

// The table that follows a heading, on pages that report the result of an
// import.
export function resultsTable(page: Page, heading: string, level: 'h2' | 'h3' = 'h2'): Locator {
  return page.locator(
    `xpath=//${level}[normalize-space()='${heading}']/following-sibling::table[1]`
  );
}

export function idFromUrl(page: Page): string {
  const parts = new URL(page.url()).pathname.split('/');
  return parts[parts.length - 1];
}

// The path of a file in e2e/fixtures, for an upload.
export function fixture(name: string): string {
  return path.join(__dirname, '..', 'fixtures', name);
}

// An upload with an extension the import pages do not allow.
export function notCsvFile(name: string): { name: string; mimeType: string; buffer: Buffer } {
  return {
    name: `${name}.docx`,
    mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    buffer: Buffer.from('not a CSV file'),
  };
}

// Sets a field that a form posts, and adds it as a hidden input when the form
// has no field with that name. Lets a test post values that the page does not
// offer, as a user could with the browser's developer tools.
export async function setPostedField(form: Locator, name: string, value: string): Promise<void> {
  await form.evaluate(
    (element, [fieldName, fieldValue]) => {
      const formElement = element as HTMLFormElement;
      let input = formElement.querySelector<HTMLInputElement>(`[name="${CSS.escape(fieldName)}"]`);
      if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = fieldName;
        formElement.appendChild(input);
      }
      input.value = fieldValue;
    },
    [name, value]
  );
}
