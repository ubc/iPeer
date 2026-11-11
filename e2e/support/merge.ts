import { expect, test, type Locator, type Page } from '@playwright/test';

export type MergeAccount = 'Primary' | 'Secondary';
export type MergeSearchField = 'Full Name' | 'Username' | 'Student No.';

export async function openMergeUsers(page: Page): Promise<void> {
  await test.step(
    'Open the merge users page',
    async () => {
      await page.goto('/users/merge');
      // Nothing is picked yet, so the form refuses to submit.
      await expect(mergeButton(page)).toBeDisabled();
    },
    { box: true }
  );
}

export function mergeButton(page: Page): Locator {
  return page.getByRole('button', { name: 'Merge' });
}

export function mergeCandidates(page: Page, account: MergeAccount): Locator {
  return page.locator(`#User${account}Account`);
}

// Sends a search and does not wait for its answer.
export async function startMergeSearch(
  page: Page,
  account: MergeAccount,
  field: MergeSearchField,
  value: string
): Promise<void> {
  await page.locator(`#User${account}Search`).selectOption({ label: field });
  const search = page.locator(`#User${account}SearchValue`);
  await search.fill(value);
  // The form has no search button: Enter in the search box looks the accounts
  // up over AJAX.
  await search.press('Enter');
}

export async function searchMergeAccount(
  page: Page,
  account: MergeAccount,
  field: MergeSearchField,
  value: string
): Promise<void> {
  await test.step(
    `Search the ${account.toLowerCase()} account by ${field} for ${value}`,
    async () => {
      await startMergeSearch(page, account, field, value);
      // The search is finished when the first option is no longer the prompt.
      const first = mergeCandidates(page, account).locator('option').first();
      await expect(first, `${account} account lookup`).not.toHaveText(/Search for|Searching/);
      await expect(first, `${account} account lookup`).not.toHaveText('-- Search failed --');
    },
    { box: true }
  );
}

export async function pickMergeAccount(
  page: Page,
  account: MergeAccount,
  label: string
): Promise<void> {
  await mergeCandidates(page, account).selectOption({ label });
}

export async function submitMerge(page: Page): Promise<void> {
  await test.step(
    'Submit the merge',
    async () => {
      // The form asks for confirmation. Accept only that question, and fail
      // when it does not come.
      let question: string | undefined;
      page.once('dialog', (dialog) => {
        question = dialog.message();
        void dialog.accept();
      });
      await mergeButton(page).click();
      expect(question, 'merge confirmation').toContain(
        'Are you sure you want to merge the two users?'
      );
    },
    { box: true }
  );
}

export function mergeDetail(page: Page, account: MergeAccount, field: string): Locator {
  return page.locator(`td#${account.toLowerCase()}${field}`);
}

export async function expectNoMergeCandidates(page: Page, account: MergeAccount): Promise<void> {
  await test.step(
    `Expect no ${account.toLowerCase()} account candidates`,
    async () => {
      const candidates = mergeCandidates(page, account);
      await expect(candidates.locator('option')).toHaveText(['-- No users found --']);
      await expect(candidates).toBeDisabled();
    },
    { box: true }
  );
}
