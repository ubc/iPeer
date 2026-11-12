import { expect, test, type Page } from '@playwright/test';

// The AJAX list (app/webroot/js/ajaxList.js) is the table on pages such as
// /simpleevaluations. Its rows come in after the page has loaded.

export async function waitForAjaxList(page: Page): Promise<void> {
  // The result count appears when the rows are in place, so a check for a row
  // that must be absent is not premature.
  await expect(page.locator('#ajaxListDiv .total-result').first()).toBeVisible();
}

// Runs an action from the menu that a right-click on a row opens. The row is
// the one with a link named `rowName`. When the action asks for confirmation,
// pass the question: the helper accepts only that question, and fails when it
// does not come.
export async function runAjaxListAction(
  page: Page,
  rowName: string,
  action: string,
  confirmation?: string
): Promise<void> {
  await test.step(
    `${action} on ${rowName}`,
    async () => {
      await waitForAjaxList(page);
      await page
        .locator('#ajaxListDiv')
        .getByRole('link', { name: rowName, exact: true })
        .click({ button: 'right' });

      let question: string | undefined;
      if (confirmation) {
        page.once('dialog', (dialog) => {
          question = dialog.message();
          void dialog.accept();
        });
      }
      // The menu items react to mouse up, which a click sends.
      await page.getByText(action, { exact: true }).click();
      if (confirmation) {
        expect(question, `${action} confirmation`).toBe(confirmation);
      }
    },
    { box: true }
  );
}
